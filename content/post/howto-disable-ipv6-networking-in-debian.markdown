---
title: "HOWTO: disable IPv6 networking in Debian"
slug: "howto-disable-ipv6-networking-in-debian"
date: "2008-12-02T22:45:39-06:00"
author: "fak3r"
categories:
- geek
- howto
tags:
- 2.6 kernel
- debian
- disable
- ipv6
- linux
- networking
- ports
- security
---

<div align="right"><img src="/2008/ipv6_ready_logo_phase1-150x150.png"></div><b>UPDATE 2</b> It's 2014 now, and this is much easier. Edit <code>/etc/sysctl.conf</code> and add:

<pre>
net.ipv6.conf.all.disable_ipv6 = 1
net.ipv6.conf.default.disable_ipv6 = 1
net.ipv6.conf.lo.disable_ipv6 = 1
</pre>

Now restart sysctl

<pre>
sysctl -p
</pre>

Done, it's off, notice you may need to address individual services to stop them from listening on ipv6 interfaces.

<hr />

<b>UPDATE</b> It's been some time since I posted this, but I just read a post called [Linux Hardening - Quick Wins](http://www.esecurityplanet.com/trends/article.php/3938786/article.htm) that reinforces my thinking on this point; if you don't need a service, you shouldn't have it running:


> _Disable IPv6_: Unless you know that you need it, disabling IPv6 is a good idea as it is hard to monitor, making it attractive for hackers, and it's also hard to spot security vulnerabilities in the protocol.


*Again, this is no shot at IPv6, merely my point that if you're not using it, you shouldn't be running it.*

Tonight I did ran netstat (`netstat -plunt`) on my [Debian](http://debian.org) server and saw that I had some ports listening via [IPv6](http://en.wikipedia.org/wiki/IPv6).  It's a shame IPv6 hasn't caught on as it's better than IPv4 in virtually every way, and it should, especially since [TCP/IPv4 was standardized in ARPANET RFC's](http://ntrg.cs.tcd.ie/undergrad/4ba2/ipng/gerd.ipv4.html)... in 1981!  Also, IPv6 provides network level security via IPSec, which enables authentication of sender and encryption of communication path, to secure communications, all fun stuff, but while some point to the fact that the Beijing Olympics used IPv6 exclusively as a point in how far it's come, that's hardly saying much when the protocol went Alpha... in **1996**!  I mean I put things off and get distracted, sure, but come on!  So while its adoption can be argued to be a case of [the chicken before the egg](http://robert.accettura.com/blog/2008/08/18/nobody-is-using-ipv6/), since I'm not using anything IPv6, nor do I or my ISP even have the capability to use it, it's silly and perhaps dangerous to leave it running with open ports.  So, if you're not using it, disable it - it's easy, just put on your pointy hat and follow along...

First we need to edit:

    
    /etc/modprobe.d/aliases


By default you will have a line like this:

    
    alias net-pf-10 ipv6


Replace that line with:

    
    alias net-pf-10 off
    alias ipv6 off


_(The second line may/may not be required with newer (2.26.+) kernels, but it won't hurt anything)_

Also, while we're at it, on your desktop machines, help out Firefox by disabling IPv6 there too.  It's simple, in the location bar enter:

    
    about:config


Then search for:

    
    network.dns.disableIPv6


and toggle its value to <code>true</code>

Well, that's it, you're now surfing with 1980s technology (just like 99.098% of the internet!)
