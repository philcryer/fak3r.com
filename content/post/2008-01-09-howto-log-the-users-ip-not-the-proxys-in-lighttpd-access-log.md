---
title: "HOWTO: log the user's IP, not the proxy's, in Lighttpd access log"
slug: "howto-log-the-users-ip-not-the-proxys-in-lighttpd-access-log"
date: "2008-01-09T10:17:41-06:00"
author: "fak3r"
categories:
- geek
- howto
- linux
tags:
- access log
- howto
- http accelerator
- IP
- lighttpd
- lighty
- referring
- reverse proxy
- squid
- varnish
- x-forwarded-for
---

 use that variable in its access logs instead of the default variable defining the referring IP.  Once we know that, the configuration is simple; in lighttpd.conf, enter this:

    
    accesslog.format = "%{X-Forwarded-For}i %l %u %t \"%r\" %>s %b /
    \"%{Referer}i\" \"%{User-Agent}i\""


For the definition of these variables, and plenty more, hit [Lighty's wiki](http://trac.lighttpd.net/trac/wiki/Docs%3AModAccessLog).  Props to the poster on the Varnish mailing list for bringing this up and reminding me to fix it!  I've sent this link to the list so now it's out there.
