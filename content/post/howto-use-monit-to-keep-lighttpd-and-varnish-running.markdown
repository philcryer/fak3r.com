---
title: "HOWTO: use monit to keep Lighttpd and Varnish running"
slug: "howto-use-monit-to-keep-lighttpd-and-varnish-running"
date: "2008-02-19T13:51:00-06:00"
author: "fak3r"
categories:
- geek
- howto
tags:
- debian
- geek
- howto
- lighttpd
- varnish
---

[, the little monitoring app we use at work to keep things sane.  I was getting around to installing it at home, but it became more urgent when Varnish went down last week; without it running there's nothing to handle requests on :80, so as a webserver it's dead. So here's my monitrc for the webserver Lighttpd fronted by Varnish, acting in the reverse proxy/http accel role. Varn is listening on 80, then, if things aren't cached, it forwards things on to Lighttpd listening on 82. Lighty also listens on the standard 443 for HTTPS requests, so we check that as well.

    
    check process varnish with pidfile /var/run/varnishd.pid
    start program = "/etc/init.d/varnish start"
    stop program = "/etc/init.d/varnish stop"
    if cpu > 60% for 2 cycles then alert
    if cpu > 80% for 5 cycles then restart
    if totalmem > 200.0 MB for 5 cycles then restart
    if children > 250 then restart
    if loadavg(5min) greater than 10 for 8 cycles then stop
    if failed host 127.0.0.1 port 80 protocol http
    then restart
    if 3 restarts within 5 cycles then timeout
    
    check process lighttpd with pidfile /var/run/lighttpd.pid
    start program = "/etc/init.d/lighttpd start"
    stop program = "/etc/init.d/lighttpd stop"
    if cpu > 60% for 2 cycles then alert
    if cpu > 80% for 5 cycles then restart
    if totalmem > 200.0 MB for 5 cycles then restart
    if children > 250 then restart
    if loadavg(5min) greater than 10 for 8 cycles then stop
    if failed host 127.0.0.1 port 82 protocol http
    then restart
    if failed host 127.0.0.1 port 443 type tcpssl protocol http
    with timeout 15 seconds
    then restart
    if 3 restarts within 5 cycles then timeout


So now we have monit watching Lighttpd, Varnish, Postifx, MySQL and OpenSSH - restarting things if they fail, and emailing me the status when they do.  Next on to some long term trending with [Cacti](http://www.cacti.net/) providing some rrd graphing and then we'll really have an idea of what this box is doing and be able to tune it accordingly.
