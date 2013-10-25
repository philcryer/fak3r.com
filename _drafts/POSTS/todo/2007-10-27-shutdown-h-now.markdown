---
author: phil
comments: true
date: 2007-10-27 23:14:06
layout: post
slug: shutdown-h-now
title: shutdown -h now
wordpress_id: 640
categories:
- geek
tags:
- bsd
---

Just shut down the old server, chavez, that was running FreeBSD 6.1 - an awesome server. I've switched to Debian, and I'm really loving it, having used it in the past I really never dug into it as a server until recently. Administration is just easier, and it's in line with me trying to cut back on things I have to do on the computer; gives me more time for other efforts. Anywho, it's down now, for the record here's the uname/uptime:

`[23:45:42] [root@chavez /home]# uname -a
FreeBSD chavez 6.1-SECURITY FreeBSD 6.1-SECURITY #0: Wed Feb 14 15:33:28 UTC 2007 root@builder.daemonology.net:/usr/obj/usr/src/sys/GENERIC i386
11:45PM up 237 days, 4:30, 1 user, load averages: 0.00, 0.00, 0.00
[23:45:45] [root@chavez /home]# shutdown -h now
Shutdown NOW!
shutdown: [pid 24665]
[23:55:00] [root@chavez /home]#
*** FINAL System shutdown message from root@chavez.cryer.us ***
System going down IMMEDIATELY
System shutdown time has arrived
Connection to chavez closed by remote host.`
