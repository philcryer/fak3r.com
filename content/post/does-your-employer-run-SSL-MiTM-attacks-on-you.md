+++
title = "Does your employer run SSL MiTM attacks on you?"
Description = "If you're working for a F500 company, most likely, yes"
date = "2015-07-22T18:23:29-06:00"
Categories = ["geek", "surveillance"]
Tags = ["ssl", "mitm", "infosec", "websense", "wsg_subca"]
+++

> __TL;DR__ companies are buying appliances that run SSL MiTM (Man in The Middle) attacks against their users, decrypting sessions on the fly without the user's knowledge. You should find out if this is happening to you.

As a self described privacy advocate, I consider myself pretty cognizant of when I might be under some sort of network surveillance; I know what to look for, and enjoy understanding ways to avoid it (often by not visiting certain sites from certain networks), but one day I hit something that surprised me. It was enough to make me take notes so that others could see if they were under a similar setup. One day, while working for __$company__, after a scheduled 'network upgrade' I was unable to interact with [github.com](https://github.com/), a site that I had been using daily up to that point. The error I'd get was...

```
✗ git pull
fatal: unable to access 'https://github.com/xxxxxxxx/xxxxxxxx.git/': server certificate verification failed. CAfile: /etc/ssl/certs/ca-certificates.crt CRLfile: none
```
This made me suspicious, so I followed the trail to learn what was happening. Obviously we know that Github's cert is valid (tech news sites would be blowing up about it if it weren't), so were we being blocked from accessing Github, or was it something with the SSL certificate that was throwing an error?

<!-- more -->

# Discovery

Digging into into it more, I asked `curl` to tell me what it knew about the certificate:

```
✗ curl -v -k https://github.com/xxxxxxxx/xxxxxxxx.git/
* Hostname was NOT found in DNS cache
*   Trying 192.30.252.129...
* Connected to github.com (192.30.252.129) port 443 (#0)
* successfully set certificate verify locations:
*   CAfile: none
  CApath: /etc/ssl/certs
* SSLv3, TLS handshake, Client hello (1):
* SSLv3, TLS handshake, Server hello (2):
* SSLv3, TLS handshake, CERT (11):
* SSLv3, TLS handshake, Server key exchange (12):
* SSLv3, TLS handshake, Server finished (14):
* SSLv3, TLS handshake, Client key exchange (16):
* SSLv3, TLS change cipher, Client hello (1):
* SSLv3, TLS handshake, Finished (20):
* SSLv3, TLS change cipher, Client hello (1):
* SSLv3, TLS handshake, Finished (20):
* SSL connection using DHE-RSA-AES256-GCM-SHA384
* Server certificate:
*    subject: businessCategory=Private Organization; 1.3.6.1.4.1.311.60.2.1.3=US; 1.3.6.1.4.1.311.60.2.1.2=Delaware; serialNumber=5157550; street=548 4th Street; postalCode=94107; C=US; ST=California; L=San Francisco; O=GitHub, Inc.; CN=github.com
*    start date: 2014-04-08 00:00:00 GMT
*    expire date: 2016-04-12 12:00:00 GMT
*    issuer: C=xx; ST=xxxxxx; L=xxxxxxxx; O=xxxxxx; CN=WSG_SubCA; emailAddress=xxxxxx@xxxxxxx
*    SSL certificate verify result: unable to get local issuer certificate (20), continuing anyway.
> GET /xxxxxxxx/xxxxxxxx.git/ HTTP/1.1
> User-Agent: curl/7.35.0
> Host: github.com
> Accept: */*
> 
< HTTP/1.1 301 Moved Permanently
* Server GitHub.com is not blacklisted
< Server: GitHub.com
< Date: Wed, 25 Jun 2014 15:10:14 GMT
< Content-Type: text/html
< Location: https://github.com/xxxxxxxx/xxxxxxxx/
< Vary: Accept-Encoding
< X-Served-By: bc4c952d089501afbfc8f7ff525da31c
< Content-Length: 178
< Age: 0
< Via: HTTPS/1.1 localhost.localdomain
< 
<html>
<head><title>301 Moved Permanently</title></head>
<body bgcolor="white">
<center><h1>301 Moved Permanently</h1></center>
<hr><center>nginx</center>
</body>
</html>
* Connection #0 to host github.com left intact
```

So it looks like SSL is being intercepted by the gateway, did this mean something (or someone?) was doing SSL decryption on outgoing traffic from our network? That would be... interesting, and possibly criminal if it was a malicious attacker, so I continued digging. Using Chrome to browse Github told me more:

```
github.com has asked Chrome to block any certificates with errors, but the certificate that Chrome received during this connection attempt has an error.

 Error type: HSTS failure
 Subject: github.com
 Issuer: WSG_SubCA
 Public key hashes:
```

So HSTS was the issue, it was not allowing this certificate. I have [HSTS set on my webservers](https://github.com/philcryer/nginx-globals/blob/master/globals/ssl.conf#L15) and, not surprisingly, Github is smart enough to do that too. [OWASP](https://www.owasp.org/index.php/HTTP_Strict_Transport_Security) defines HSTS as:

> HTTP Strict Transport Security (HSTS) is an opt-in security enhancement that is specified by a web application through the use of a special response header. Once a supported browser receives this header that browser will prevent any communications from being sent over HTTP to the specified domain and will instead send all communications over HTTPS. It also prevents HTTPS click through prompts on browsers.  The specification has been released and published end of 2012 as [RFC 6797](https://tools.ietf.org/html/rfc6797) (HTTP Strict Transport Security (HSTS)) by the IETF.

So what happens is that you after you hit an SSL protected site, it sends a response header to verify the SSL has not been intercepted or otherwise messed with during the round trip. This was the issue blocking my requests to Github. Meanwhile the HSTS error was pretty revealing, you see it above and in this screenshot:
<div align="center"><img src="/2015/ssl-WSG_SubCA.png" border="2" ><br />Verified by WSG_SubCA huh?</div>
The issuer of the SSL certificate isn't something that we'd expect (GitHub, Inc. or github.com), it's __WSG_SubCA__. What the hell is that? So with some quick sleuthing on [DuckDuckGo](https://duckduckgo.com) I found that we were dealing with a [Websense Content Gateway](https://www.websense.com/content/web-security-gateway-features.aspx). With impressive (sounding) whitepapers talking about, _"Next Generation Secure Gateways"_ with, _"Advanced Classification Engine"_ it was clear we were dealing with an _Enterprise Solution(tm)_, so why was it stripping the right SSL and giving me a Websense CA instead? Reading up on the Websense Content Gateway (WCG), I found the helpful [release notes](https://www.websense.com/content/support/library/web/v78/wcg_release_notes/78rn_intro.aspx#689076) that spelled out how it worked its magic, under _"New in Websense Content Gateway v7.8.1"_ specifically:

* Support for SSL (HTTPS) decryption, analysis, and re-encryption has been re-engineered for version 7.8.1" and inserting our own generated SSL and injecting that into the session. SSL support is now part of the core Content Gateway proxy.

Other interesting features of the product include:

* Pre-installs the trusted Root CA tree used by Mozilla Firefox
* Can be used with explicit and transparent proxy deployments
* Can be configured to accept HTTPS traffic from multiple inbound ports (client to proxy)
* Provides certificate handling and management features
* Includes configurable certificate validation features 
* Includes robust decryption bypass features to simplify the process of maintaining compliance with government and corporate laws and policies, and to whitelist trusted sites and blacklist untrusted sites
* Allows for customizable messages to users when there is a certificate verification failure or connection error

## How does this work?

So now we know what's happening, but how is it happening? Open source security community [OWASP](https://www.owasp.org) has a great image showing what's going on:
<div align="center"><img src="/2015/owasp-man_in_the_middle.jpg" alt="What is a MiTM attack? (OWASP)" border="2"><br />A simplified example, just ignore that the interceptor here is labed as an Attacker</div>
But if this was happening for all other users on the network, they would be complaining and eventally discover the bogus __WSG_SubCA__, why aren't they getting tipped off? Simple, the network admins control what happens when a system logs into the network via [Active Directory](https://en.wikipedia.org/wiki/Active_Directory), the Microsoft directory service. It's likely that they drop the __WSG_SubCA__ on to the user's system, and tell the browser that it's "trusted" so it won't give an error when it uses it. See in the following image where it says, "Root Certificates Installed in Browser":
<div align="center"><img src="/2015/https_bad_filtering-3.png" alt="Illustration from iboss.com" border="2"></div>
And I found an article that talks about how [Active Directory Certificate Services](http://blogs.technet.com/b/privatecloud/archive/2013/12/06/automating-active-directory-certificate-services-with-windows-powershell-part-1.aspx) works, and it can distribute any SSL certificate to a user's systems. WSG even has a [simple web GUI](http://kb.websense.com/pf/12/webfiles/Webinars/webinar_pdf/February%202010_WebinarSlides.pdf?33) to help you create your own _Internal Root CA_. Problem solved solved on your end! But if this is what's happening it has terrible privacy and security implications, if all user's SSL traffic was decrypted and captured things like banking, heath information and other PII (Personally Identifiable Information) that must remain secret. But when I hit sites like [Paypal](paypal.com) and [Mastercard](mastercard.com) I noticed, I was getting the correct SSL certificates, so there was obviously a whitelist in play here. This was also verified by reviewing the [release notes](http://kb.websense.com/pf/12/webfiles/WBSN%20Documentation/WCG/WCG_v7.5/WCG75ReleaseNotes.pdf?5) it talks about how the "SSL decryption bypass" works:

> __Web Security Gateway: SSL decryption bypass__
To support organizations using SSL Manager to manage encrypted traf fic, and who do not want to decrypt HTTPS sessions that users establish with sens itive sites, such as person al banking or health provider sites, administrators can now specify cate gories of sites that will bypass SSL decryption. A list of hostnames or IP addresses for which SSL decryption is not performed can also be maintained.  These capabilities are configured on the __Scanning > SSL Decryption Bypass page__ in TRITON - Web Security (Web Security Manager).

One of their [Webinar from early 2010](http://kb.websense.com/pf/12/webfiles/Webinars/webinar_pdf/February%202010_WebinarSlides.pdf) they state that their SCIP (Secure Content Inspection Proxy) was setup by a 3rd party:

* Websense has contracted with Microdasys to provide the HTTPS termination process used by WCG.

and that once the HTTPS/SSL is terminated:

* The data is decrypted, forwarded to WCG.
* WCG applies inspection rules (ie. WSE, Analytics)
* encrypted and sent to its destination.
* Applies security policies to all encrypted inbound and outbound Internet traffic.
* Data can be decrypted and hence inspected for malware.

As you can see, they give positive reasons for doing this (malware is bad). Later, in a [2013 presentation](http://www.websense.com/assets/support/webinar/Presentation/2013_01_Slides.PDF) they describe more the software can do:

* An extended.log example without SSL decryption:

```
10.5.144.32 - [12/Jan/2013:15:43:51 -0000] "CONNECT www.cia.gov:443/ HTTP/1.0" 200 127 200 0 0 0 383 127 542 76 0
```

* An extended.log example with SSL decryption:

```
10.5.144.32 - [12/Jan/2013:15:43:51 -0000] "CONNECT www.cia.gov:443/ HTTP/1.0" 200 127 200 0 0 0
383 127 542 76 0
10.5.144.32 - - [12/Jan/2013:15:43:52 -0000] "GET http://www.cia.gov/javascript/register_function-
cachekey1018.js HTTP/1.1" 200 52663 200 52663 0 0 840 297 829 287 0
10.5.144.32 - - [12/Jan/2013:15:43:53 -0000] "GET http://www.cia.gov/css/IEFixes.css HTTP/1.1" 200
3642 200 3642 0 0 810 279 799 269 0
10.5.144.32 - - [12/Jan/2013:15:43:53 -0000] "GET http://www.cia.gov/css/ciatheme-index.css
HTTP/1.1" 200 10657 200 10657 0 0 818 281 807 271 0
10.5.144.32 - - [12/Jan/2013:15:43:53 -0000] "GET http://www.cia.gov/css/base-cachekey6837.css
HTTP/1.1" 200 59571 200 59571 0 0 821 281 810 271 0
10.5.144.32 - - [12/Jan/2013:15:43:53 -0000] "GET http://www.cia.gov/javascript/javascript.js HTTP/1.1"
200 6092 200 6092 0 0 820 296 809 286 0
```

I found it intersting they used the URL for the [CIA](www.cia.gov) as an example. Later more justification that no one in security could say no to:

* SSL decryption improves adherence to organisational policies
* Access control
* Monitoring
* Reporting

But then they seem to be reaching a bit with...

* Improves organisational and user security
* Reduced risk of interception

Whatever, marketing can sell anything, but my favorite discovery from Websense was that they specically stated on [page 5](http://www.websense.com/assets/support/webinar/Presentation/2013_01_Slides.PDF?5) of the presentation, that _"SSL decryption uses the Man In The Middle (MitM) method"_
<div align="center"><img src="/2015/ssl-mitm-screenshot.png"  alt="SSL decryption uses the Man In The Middle (MitM) method" border="2"><br />One picture is worth 1000s words, eh?</div>
So even in their (clearly early, 2013) promotion materials, they describe this functionality for what it is: a Man in The Middle attack -or- MiTM (I prefer to have the T capitalized, call me old fashioned).

## Why didn't I see this?

So why didn't I see this behavior? Why did I get an error when everyone else thought it was smooth sailing? I'm one of those geeky-weirdos that has had a long history of not wanting to work on Windows machines, in general. Usually at a new gig I'll get the client's permission to either dual-boot Windows and Linux, just wipe the machine and run Linux, or use my own personal laptop running Linux. There are always, "but you need windows to xxxxxx" reasons why this can't work, but I'm always able to demonstrate work arounds to cover these issues. These days the easiest way to accomplish this now is to run a  virtual desktop infrastructure (VDI) that mimics, or directly logs into a Windows server via remote desktop protocol. Sometimes this is launched via a 'plugin' in the browser so just visiting a URL will work, other times running commandline apps like `rdesktop` or similar gets you a window right on the RDP terminal. Point is, since I'm not running Windows I'm not getting the system administering setups, so I didn't recieve their fake Root CA to validate sites for me blindly. I know I'm an edge case here, but sometimes that's what you need to learn about what's really going on.

## What is Websense?

Ironically I knew about [Websense](http://www.websense.com/) from "back in the day", in another life, when I actually administrated a Websense box, but at that time we used it to tally pageviews and provide other website analytics (which is a different kind of privacy violation you should know my stance on). Looking online I found that over the years that Websense has done [quite well for themselves](http://www.ibtimes.com/raytheon-buys-cybersecurity-company-websense-19-billion-1888565), _"Texas-based Websense was founded in 1994 and has over 1,500 employees located around the world"_ when, earlier this year it was bought by _"U.S. weapons and electronic equipment manufacturer Raytheon announced Monday it will buy the cybersecurity company Websense for $1.9 billion"_. By the way, when I go to [DEF CON](defcon.org) each year, the 'cybersecurity' folks I'm meet don't look like the Raytheon Cyber executives:
<div align="center"><img src="/2015/raytheon-cybersecurity.jpg" border="2" alt="Raytheon Cyber Programs Vice President Jeffrey Snyder (right) speaks at the Reuters Cybersecurity Summit in Washington May 13, 2013. Reuters/Hyungwon Kang"><br />These are not the cybersecurity experts you're looking for!</div>

... but I digress.

# End state

Look, I know what I'm going to hear, and I totally get it; this is the __$company__ network and resources, they are ultimately responsible for defending themselves from malicious software entering the network and for misuse that their users might, knowingly or unknowingly, do. The point is that __the users don't know that their privacy and security is being thrown out out the window in the guise of security__. Yes they might have seen a EULA or other notice stating that the company owns everything, but for years we have trained users that if they use HTTPS and they see that 'Lock' that they have end-to-end encryption, they have privacy, but they don't know that this can be completely false if the company is using an appliance that circumvents that functionality and silently installs a hand-made, bogus Root CA to lie to them. Note that the action of blocking or censoring web content on a national level is considered evil, you have to admit that doing the same (maybe more so) and not implictly informing users how invasive their monitioring is, is a bit evil as well.

So, does __your__ employer run SSL MiTM attacks on you? Take a closer look at the SSL certs you get when you load an HTTPS page, and see what you can find.

## Further reading...

* [Security risks are posed by vendors deploying SSL Intercepting proxies on user desktops)](http://security.stackexchange.com/questions/82035/what-security-risks-are-posed-by-software-vendors-deploying-ssl-intercepting-pro) (Stackexchange Security)
* [Check if your company/ISP is intercepting your HTTPS traffic](http://www.boards.ie/vbulletin/showthread.php?p=84125199) (Irish discussion forum)
* [How the NSA, and your boss, can intercept and break SSL](http://www.zdnet.com/article/how-the-nsa-and-your-boss-can-intercept-and-break-ssl/#!) (ZDnet)
* [Should Companies Be Forced to Enable Surveillance and Compromise Security? The Government Thinks So](https://www.aclu.org/blog/washington-markup/should-companies-be-forced-enable-surveillance-and-compromise-security) (ACLU)
* [The Growing Need for SSL Inspection](https://www.bluecoat.com/security/security-archive/2012-06-18/growing-need-ssl-inspection) (Blue Coat)
* [Security issue discovered: Are you performing SSL decryption with Websense?](http://community.websense.com/forums/t/16954.aspx?PageIndex=1) (Websense Community Forum)
* [Websites Must Use HSTS in Order to Be Secure](https://www.eff.org/deeplinks/2014/02/websites-hsts) (EFF)
* [Google says Turkey is intercepting web traffic to spy on users ](http://www.theverge.com/2014/3/30/5564294/google-says-turkey-is-intercepting-web-traffic-to-spy-on-users) (The Verge)
