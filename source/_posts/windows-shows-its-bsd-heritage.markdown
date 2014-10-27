---
author: phil
comments: true
date: 2005-09-27 20:03:00
layout: post
slug: windows-shows-its-bsd-heritage
title: Windows shows its BSD heritage
wordpress_id: 86
categories:
- humor
tags:
- bsd
---

It’s well known that MS utilized BSD code (which is allowed under the [BSD License](http://en.wikipedia.org/wiki/BSD_License)) in various places in Windows, but it’s still fun to see it in there.  Unhappily I’m using XP at my current consulting gig, but I’ll fix that soon.  If you are as well, drop to the cmd.exe window and do the following:

    
    <code>c:> strings.exe c:\\WINDOWS\\system32\\ftp.exe | grep Copyright</code>


You’ll get back the following:

    
    <code>@(#) Copyright (c) 1983 The Regents of the University of California.</code>


Incidentally, on my [FreeBSD](http://freebsd.org) server at home it shows a bit more up to date code:

    
    <code>[pepe:/usr/bin]$ strings /usr/bin/ftp  | grep Copyright;
    strings /usr/bin/ftp  | grep California
    
    @(#) Copyright (c) 1985, 1989, 1993, 1994
    The Regents of the University of California.  All rights reserved.</code>


And yes, you’d expect it to be a bit out of date; there’s no reason to use ftp nowadays, as it’s completely insecure.  [OpenSSH](http://www.openssh.com/) provides scp and sftp for secure transfers, and you can tunnel almost anything else through it (I do rsync over ssh for backups) so there’s no reason not to use it.
