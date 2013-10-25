---
author: fak3r
comments: true
date: 2012-06-22 18:13:19
layout: post
slug: howto-fix-no-route-to-host-in-openbsd
title: HOWTO fix no route to host in OpenBSD
wordpress_id: 3897
categories:
- bsd
- geek
- howto
tags:
- bsd
- default route
- gateway
- networking
- no route to host
- openbsd
- route
---

[![OpenBSD](http://fak3r.com/assets/openbsd_96x96.png)](http://fak3r.com/?attachment_id=3900)I installed [OpenBSD](http://www.openbsd.org/) 5.1 recently on my new box that's running [Proxmox](http://pve.proxmox.com/wiki/Main_Page) 2.1. I want OpenBSD to run [pf](http://www.openbsd.org/faq/pf/) to provide a firewall to protect all the other virtualized hosts on the box. I don't remember what I put for my default route during the install, and that came back to bite me as I couldn't get outside of my network. Issuing a ping would resolve a hostname (I run a local DNS server) but then it didn't have a route to the resolved IP, so it would fail with the error `ping: sendto: No route to host`

    
    # ping www.google.com
    PING www.l.google.com (74.125.225.208): 56 data bytes
    ping: sendto: No route to host
    ping: wrote www.l.google.com 64 chars, ret=-1
    ping: sendto: No route to host
    ping: wrote www.l.google.com 64 chars, ret=-1
    --- www.l.google.com ping statistics ---
    2 packets transmitted, 0 packets received, 100.0% packet loss


<!-- more -->

It took a bit of digging online, but in the end it was a simple fix; delete the default route and re-add it (I put in my gateway's IP)

    
    route delete default
    route add default 192.168.1.1


Now everything works as it should.

    
    # ping www.google.com 
    PING www.l.google.com (74.125.225.209): 56 data bytes
    64 bytes from 74.125.225.209: icmp_seq=0 ttl=50 time=40.862 ms
    64 bytes from 74.125.225.209: icmp_seq=1 ttl=50 time=38.337 ms
    64 bytes from 74.125.225.209: icmp_seq=2 ttl=50 time=38.500 ms
    --- www.l.google.com ping statistics ---
    3 packets transmitted, 3 packets received, 0.0% packet loss
    round-trip min/avg/max/std-dev = 38.337/39.233/40.862/1.153 ms


Of course use a different IP if your gateway is different, but after that you'll be like me; all ready to setup pf on the network!
