---
author: phil
comments: true
date: 2008-01-09 10:17:41
layout: post
slug: howto-log-the-users-ip-not-the-proxys-in-lighttpd-access-log
title: 'HOWTO: log the user''s IP, not the proxy''s, in Lighttpd access log'
wordpress_id: 684
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

![Lighttpd - fly light](http://fak3r.com/wp-content/uploads/2008/01/light_logo_170px.png)When you run a webserver behind a reverse proxy or HTTP accelerator like [Squid](http://www.squid-cache.org) or [Varnish](http://varnish.projects.linpro.no/), the webserver access logs will display the IP of the proxy (generally 127.0.0.1) instead of the end user's IP.  This not only breaks any kind of tracking or reporting you want to run against your webserver logs, but it also takes away a datapoint I've had use for in general server admin tasks. This server runs Varnish in front of [Lighttpd](http://www.lighttpd.net/), and it reveals the end user's IP in the header as _X-Forwarded-For_, so it's just a matter of making Lighttpd (lighty) use that variable in its access logs instead of the default variable defining the referring IP.  Once we know that, the configuration is simple; in lighttpd.conf, enter this:

    
    accesslog.format = "%{X-Forwarded-For}i %l %u %t \"%r\" %>s %b /
    \"%{Referer}i\" \"%{User-Agent}i\""


For the definition of these variables, and plenty more, hit [Lighty's wiki](http://trac.lighttpd.net/trac/wiki/Docs%3AModAccessLog).  Props to the poster on the Varnish mailing list for bringing this up and reminding me to fix it!  I've sent this link to the list so now it's out there.
