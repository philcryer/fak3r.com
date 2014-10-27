---
author: phil
comments: true
date: 2007-10-10 10:13:54
layout: post
slug: howto-notes-on-securing-debian
title: 'HOWTO: notes on securing Debian'
wordpress_id: 602
categories:
- geek
- howto
tags:
- debian
- howto
- sysadmin
---

Looking over the Debian own [harden-doc](http://www.debian.org/doc/manuals/securing-debian-howto/) guide online, (which is a monster of a resource) as well as [Debian Help's security page](http://www.debianhelp.co.uk/security.htm) gave me some excellent new ideas on how to secure Debian and Linux in general.   Also today i found a netstat command with some nice switches to help you figure out what is listening on each port in an easy to read layout, -plunt:

    
    netstat -plunt


Plus it's fun to say, 'plunt'.  Lastly there's a good overview of [deborphan](http://packages.debian.org/deborphan) (which assists you in keeping your system clear of unneeded packages) with coverage on how to use it at [Debian Adminstrator.org](http://www.debian-administration.org/articles/134). But in the comments a thread talks about how [it's better to use aptitude](http://www.debian-administration.org/articles/134#comment_19), as this does it automatically.
