---
title: "mod_security for Apache"
slug: "mod_security-for-apache"
date: "2006-12-21T11:44:51-06:00"
author: "fak3r"
categories:
- geek
tags:
- security
---

![Mod_security](http://fak3r.com/wp-content/uploads/2006/12/mod_security.gif)I've worked with [mod_security](http://www.modsecurity.org/) before, but now it's running on this webserver, as I've just seen a ton of crap being thrown at the server.  Webservers are just a good target, they're out there and they usually 'just work' so most people don't keep on top of them.  Plus, plenty of crafted URLs can do funny POST or GET commands and cause trouble, or worse, expose a system that is vulnerable to SQL injection attacks.  Since I last looked into mod_security they've been acquired, which explains the marketing verbiage they list:


> ModSecurity is an embeddable web application firewall. It provides protection from a range of attacks against web applications and allows for HTTP traffic monitoring and real-time analysis with no changes to existing infrastructure.  It is also an open source project that aims to make the web application firewall technology available to everyone.


But yeah, as long as it stays Open Source, I won't complain (that much).  So this goes steps beyond earlier IDS (intrusion detection system) like Snort, since with mod_security it is set up to do one thing; to protect Apache from being attacked.  Of course you can place rules to blocks all sorts of stuff, to redirect requests, to watch for malformed URLs and even run within a chrooted environment.  This is good stuff, and it's very simple to get the basics up and running via this [howto](http://www.howtoforge.com/apache_mod_security).  From there monitor your modsec.log file and adjust accordingly.  I can see this being very useful to large environments that run Apache, hopefully I'll be able to integrate some of this at my new position.


> 
