title: Is your employer running SSL MiTM attacks on you?
date: 2014-12-15 21:14:22
tags: geek,
infosec
---
_TL;DR_ companies are running SSL MiTM (Man in The Middle) attacks, decrypting users sessions without the user knowing it. Find out if this is happening to you.

I have a story from when I was working for $COMPANY that bothered me enough to take notes and want to pass it on so others would know if they were being similarly watched. So one day at work, after a 'network upgrade', I was unable to interact with github.com, a site that I use daily. The error I'd get was...

open source mitm https://mitmproxy.org/

look: assets/2014/ssl-WSG_SubCA.png

```
➜  infra_as_code_examples git:(master) ✗ git pull
fatal: unable to access 'https://github.com/rueedlinger/infra_as_code_examples.git/': server certificate verification failed. CAfile: /etc/ssl/certs/ca-certificates.crt CRLfile: none
```

This made me suspicious, so I followed the trail. Obviously we know that Github's cert is valid (if it wasn't for any reason, tech news sites would be blowing up about it), so were we now blocked from accessing Github, or do am I missing a CA cert? Looking into it more:

```
➜  infra_as_code_examples git:(master) ✗ curl -v -k https://github.com/rueedlinger/infra_as_code_examples.git/
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
> GET /rueedlinger/infra_as_code_examples.git/ HTTP/1.1
> User-Agent: curl/7.35.0
> Host: github.com
> Accept: */*
> 
< HTTP/1.1 301 Moved Permanently
* Server GitHub.com is not blacklisted
< Server: GitHub.com
< Date: Wed, 25 Jun 2014 15:10:14 GMT
< Content-Type: text/html
< Location: https://github.com/rueedlinger/infra_as_code_examples/
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

So it looks as if SSL is being intercepted by the gateway, does this mean they were doing SSL decryption on outgoing traffic to * hosts? Using Chrome trying to browse github tells me there's an HSTS failure, which may answer that for me.

```
github.com has asked Chrome to block any certificates with errors, but the certificate that Chrome received during this connection attempt has an error.
Error type: HSTS failure
Subject: github.com
Issuer: WSG_SubCA
Public key hashes:
```

We saw that same Issuer in the curl example, WSG_SubCA, so after some slething over on [DuckDuckGo](https://duckduckgo.com) I found that we were dealing with the [Websense Content Gateway](https://www.websense.com/content/web-security-gateway-features.aspx). With impressive (sounding) whitepapers talking about, _"Next Generation Secure Gateways"_ with, _"Advanced Classification Engine"_ it was clear we weren't dealing with some proprietary off the shelf vaporware. Still, why were we being presented a CA from Websense? Wasting time on their corporate site wasn't helping, so back to DuckDuckGo where I found some announcements talking about the advacnced features of this next genenerate gateway. 

https://www.websense.com/content/support/library/web/v78/wcg_release_notes/78rn_intro.aspx#689076




http://kb.websense.com/pf/12/webfiles/Webinars/webinar_pdf/February%202010_WebinarSlides.pdf


so cert issue here is WSG (ng that means Websense Gateway) so it looks like we're using the feature described on the page: "New in Websense Content Gateway v7.8.1", specifically:

> Support for SSL (HTTPS) decryption, analysis, and re-encryption has been re-engineered for version 7.8.1" and inserting our own generated SSL and injecting that into the session. 


In a presentation on this by a Websense employee, they specifically say:

• SSL decryption uses the Man In The Middle (MitM) method 

http://www.websense.com/assets/support/webinar/Presentation/2013_01_Slides.PDF

Additionally, they show its functionality here:

Comparison of connection logs with and without SSL decryption
enabled
– An extended.log example without SSL decryption:
10.5.144.32 - [12/Jan/2013:15:43:51 -0000] "CONNECT www.cia.gov:443/ HTTP/1.0" 200 127 200 0 0 0 383
127 542 76 0
© 2012 Websense, Inc.
4Why enable SSL decryption? (Cont’d)
– An extended.log example with SSL decryption:
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

Websense helps companies justify this with ideas like:

• SSL decryption improves adherence to organisational policies
– Access control
– Monitoring
– Reporting
• Improves organisational and user security
– Reduced risk of interception
– Adds the ability to control hosts and the Categories users can browse

and look - I totally get that, this is the companies' network, and they are responsible for defending it from malicious software entering the network... but people (users) don't know this, they are trained (by infosec experts like us) that if they use HTTPS and they see the 'Lock' that they have end-to-end encryption, and if they're running Windows within the company the new Websense CA cert is installed automagically so they don't know anything differently.


