## Abstract:

Users' online privacy and security has always relied on an implicit trust in a system that we know was not designed to be trustworthy. With companies putting profit before user security, breached accounts, leaked passwords, and exposed personal data are all too common. Meanwhile, revelations from Edward Snowden have shown that governments are taking advantage of the lack of security online in secret, using 0days (zero days) to spy on users or infiltrating foreign utilities for nefarious purposes. Others operate in the open, limiting freedom by censoring citizens from accessing the open internet.  We can't count on companies or goverments to look out for our best interests, so we need to start to learn how to be smarter online. In this talk we'll cover the concerns and attack vectors we all face online, along with ways to address these threats using open source tools, methods, and concepts to protect our privacy and security. So don't panic...if you've ever been online this talk is for you. Come prepared to take away step-by-step instructions on tools and methods to use to improve your privacy and security online.

## Speaker Bio(s):

Phil Cryer is a hacker, open source technologist at-large, writer, and occasional speaker. He has spoken worldwide at infosec conferences like DEF CON, DerbyCon, BsidesLV, SecureWorld and SEMAFOR (Warsaw Chapter), as well as talks about utilizing open source software in the global biodiversity realm to facilitate open data exchange over the internet. As an active member of the EFF (Electronic Frontier Foundation), he leads and moderates discussions about electronic rights and civil liberties while educating users how their data is used by companies without their knowledge. With over fifteen years of industry experience, his research has focused on companies' weaknesses in protecting customer data, and failures in keeping that data private. He knows there’s always another way to do it, so he is constantly learning new techniques to solve technical challenges, usually by hacking on his home network and sharing what he finds online. His long running blog (fak3r.com) serves as a sharing hub for technical how tos, and allows him to build on his ideals using an iterative development process. Slides for his talks are archived on Slideshare, and all code or scripts he writes are available as open source on Github. He listens to music constantly, holds a bachelor’s degree in fine arts, is a practicing troublemaker, and an accidental tourist.

## Detailed Outline:

### Introduction

* whoami
- work history with open source and privacy, plus general information technology roles
- past presentations covering global data sharing, online privacy, infosec, information security
- all of my other talks are all available online (https://www.slideshare.net/phil.cryer) specifically ones dealing with online privacy and security from years past (https://www.slideshare.net/phil.cryer/0nline-privacy-concerns)
- ideals like “With social media, users’ vanity has trumped previously held mores concerning privacy” me, 2011
- 15 years of being online, and blogging for the most of them, at https://fak3r.com reveals howtos where I developed/evolved my ideas and methods (howtos, exploration, investigation)
- what I do day to day and how I've evolved my methods and approaches to staying secure online
	
### Expectations

* the WHAT of this talk; specifically what the audience will learn from the presentation, notably
- online concern for security and privacy, addressed by understanding and utilizing tools
- client applications and configurations to greatly increase online privacy
- server applications deployed locally or remotely, the pros and cons of these tools, including (minimal) cost considerations
* a balanced approach to online security and privacy versus convenience; if something is too difficult, users will find workarounds 
- don't do this! Instead find a balance with what you're comfortable with - addressing your own threat profile versus what tools you are comfortable using

### Concerns
* the WHY of this talk; why should users be concerned about their privacy and security online
- within China: government overreach in surveillance 
- ingress and egress in China: censorship (the great firewall of china*)
- global information sharing: Edward Snowden revelation of the Five Eyes (FVEY)
- "an intelligence alliance comprising Australia, Canada, New Zealand, the United Kingdom and the United States."
- another document leaked by Edward Snowden says that there is another working agreement amongst 14 nations officially known as SIGINT Seniors Europe, or "SSEUR". These "14 Eyes" consist of the same members of 9 Eyes plus Germany, Belgium, Italy, Spain and Sweden." 
- corporate social media companies putting profit over user's privacy
- global examples: facebook, twitter, youtube, etc, plenty to scope out here
- within China: WEIBO (微博), RENREN (人人) WECHAT (微信) 4. YOUKU TUDOU (优酷土豆) 5. DIANPING (大众点评)
- talk about examples that the audience would be most interested (and threatened) by

### Getting started
* General concepts
- almost all examples and concepts will work REGARDlESS of operating system (stay agnostic)
- but when taking things into consideration, do the best you can, ala: windows < macOS < Linux < *BSD
* how much will all this cost?
- all of these client-side tools are open source, and/or, free
- all server-side tools are free, but some *may* require local, or remote hardware for enhanced privacy, these introduce some, but low, cost (approximately 5 USD or 31 CNY per month)
- hardware token options for 2FA or OTP usage, to really secure things on the client side (approximately 50 USD / 316 CNY)
- (optional) password server fee, but for advanced features (2FA) there is an annual cost (USD 24 / 152 CNY)

### Tools

#### client-side

- overview of what will be covered in this section; client-side user tools - browsers, browser plugins, password managers, instant messaging, and email
https://privacy.haus/

##### browsers

- much like the OS exercise, go for what fits your threat profile, in general chrome < chromium < firefox < SRWare Iron < Tor Browser bundle
- all options are free - but there differences with all of these choices
- chrome - for profit, closed source because they add proprietary tools, and an additional cost (your data)
( plugins - video AAC, H.264, and MP3, Adobe Flash (PPAPI). Chrome includes a sandboxed Pepper API (PPAPI) Flash plug-in that Google automatically updates along with Chrome, google update toos, extention restrictions, crash and error reporting (telementary data), and security sandbox (which can be on in Chromium too!)
- chromium - google's open source option, without the features (bloat?) above
- firefox - open source browser, not from a for profit organization, promises to put the user (and the user's privacy) first
- SRWare Iron - "the future of the browser", more analogus to chromium, "SRWare Iron: The browser of the future - based on the free Sourcecode "Chromium" - without any problems at privacy and security"
- Tor browser bundle - an easy way to use a browser (firefox), and connect to the Tor network, "Tor is free software for enabling anonymous communication. The name is derived from an acronym for the original software project name "The Onion Router" - but lets define what anonymous is in this case, and what you can (and can't) count on it for
* browser-plugins
- to enhance your online security and privacy online it is essential to use browser plugins to change settings, and block tracking where possible
- https everywhere - a plugin from the Electronic Frontier Foundation (EFF) that automatically keeps you on the more secure https protocol, when available on the site
- privacy badger - another EFF plugin that promotes, "..a balanced approach to internet privacy between consumers and content providers by blocking advertisements and tracking cookies that do not respect the Do Not Track setting in a user's web browser. While some of its code is based on Adblock Plus, Privacy Badger only blocks those ads which come with embedded trackers."
- uBlock Origin  - a traditional "ad blocker" that blocks ads, tracking, etc from a domain block list that you can control. This blocks far more than Privacy Badger, so understand how it works, what it does and why it can break some sites.
- a password manager plugin (see next section)

##### password management
- forget your passwords!
- tools to manage your passwords, help with generation of very complex passwords, allows you to use different passwords on EVERY site you have to login to, and you will never have to know/remember the passwords
- open source options - keypass, what that will get you as far as security and convience
- lastpass, free to use basic service, cross platform (win, mac, linux, android, ios) and recommended, not expensive to become a premium user (USD 24 / 152 CNY), this opens up options like using a 2FA option (yubikey, google auth, etc) 
- talk about using yubikey to protect your account, so you'll need username/password and yubikey to access your account. Via Android you can also use the key via NFC, then later fingerprint option
- Yubikey hardware token options for 2FA or OTP usage, to really secure things on the client side, costs (50 USD / 316 CNY)
- 1password - popular macOS option, but no linux client 
- point is, if you're not using a password manager, use one... NOW	
	
##### email
- yes, we're still talking about this! 
- traditional email is not secure, and really this is by default
- email that is 'secure' may be secure in your browser (https), or your client (tls), but once it gets securely to the mail server it may/may not be fully encrypted as it passes over the internet - so that's not secure 
- so again, considering your threat profile, how much can you do / or need to do?
- send PGP encrypted messages (not for the faint hearted, but getting easier with newer tools), but requires both users to be pretty into wanting to do crypto (not very practical)
- protonmail - easy to do encrypted email "end to end" in the browser, but both users need to be protonmail users, and send/check from protomail... this works, but again it's leaving out those not in the know 
- zerobin - while not email, an approach more analogus to sending PGP encrypted messages, here a pastebin like service runs 'zerobin', a service that is encrypted in the browser, provides a unique URL to share that can be password protected, with messages that can be auto deleted after so many days, or the popular 'burn after reading' feature where a message goes away once it's read. Passing messages this way is not as secure as PGP b/c you can't control the user side, but with a password restriction and BAR option, you can at least verify after the message is read that it was read by the intended individual

##### IM / SMS / text messaging	

- while IM/SMS/text messaging used to be as insecure as email, that's changed in many ways

* IM, but not AIM anymore! sorry. the issues with most IM as they're not federated like email, so if you use one kind of IM you can't talk to the other (well without some work-arounds)
- do you need IM, or can you just do IRC via TLS? A lot of people do (me) - also, change your password one you go encrypted
- Tox - no central server, the system is distributed, peer-to-peer, and end-to-end encrypted. This is a great solution, but requires both users to be running TOX
- chat secure - an XMPP based optoin, can use existing google accounts for auth, or public servers, but also supports OMEMO encryption and OTR encryption over XMPP. 
- Riot - new option, uses the (matrix protocol https://matrix.org/) over XMPP
- keybase - somewhat new solution that uses PGP, but makes it much easier to manage and communicate. users use online posts to verify themselves, showing they are who they are on all those other sites, giving you a decent amount of certainty of who it is you're talking to. Really nice desktop/mobile chat client, also allows for group chat, shared git repo and more - ALL ENCRYPTED. makes it easy to have a public/private way to reach people - follow me, I'm fak3r

* SMS / text messaging
- this allows you to use SMS to securely communicate, and currently there's one game in town
- signal - allows E2E (end to end) encryption between you and another signal user, otherwise it functions as a normal (unencrypted) SMS client, still it's good to have it so you can communicate securely over SMS
- they now have an excellent desktop client, but it only works to chat with your other signal contacts, again, insuring security

* other?
- big name options, facebook messanger, whats app, telegram, are more secure than old options, the first two actually use the signal protocol
- these options are still owned by big, for profit companies. consider your threat profile when going this route!

##### server-side

- this is where using open source really shines 		

* LOCAL
- Raspberry Pi (40 USD / 253 CNY)
- pi-hole - A black hole for Internet advertisements, think an ad-blocker, but at the network level - so protecting all devices
- tor - tunnel through other tor nodes to provide anonymity, again, not just for one client but for the whole network
- dns (unbound) - running your own DNS server can help you get results that others would rather not have you get, or get around blocks
- there are other options here we'll explore and list for the talk - tons of cool things this opens up

* REMOTE
- vps, like digital ocean, or aws (approximately 5 USD or 31 CNY per month)
- streisand (automated vpn setup) Streisand sets up a new server running your choice of L2TP/IPsec, OpenConnect, OpenSSH, OpenVPN, Shadowsocks, sslh, Stunnel, a Tor bridge, and WireGuard
- this is almost a swiss army knife and it is EXTREMELY easy to setup - it uses Ansible and the cloud APIs to run locally and setup your cloud server
- you can run the LOCAL options here too, but this way you could get around geo-blocks as you could VPN (or connect another way) to the host before going to the destination

### Conclusion

* using these ideas will make you more secure and private online
* plus, best of all, will help you understand what you're exposing yourself to when you're online. (the real lesson)
- feel free to reach out, stay in touch, share contact deets, twitter, email, keybase(!), etc
- thanks to def con china, the entire community, freaks on twitter that have given me confidence to share things I know about, and ... you

