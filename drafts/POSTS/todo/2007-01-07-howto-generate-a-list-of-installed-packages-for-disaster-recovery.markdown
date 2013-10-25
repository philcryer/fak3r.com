---
author: phil
comments: true
date: 2007-01-07 21:25:48
layout: post
slug: howto-generate-a-list-of-installed-packages-for-disaster-recovery
title: HOWTO generate a list of installed packages for disaster recovery
wordpress_id: 330
categories:
- geek
- howto
tags:
- howto
---

I came across [this page](http://www.arsgeek.com/?p=564) again, seems they took my advice to heart on the one line command to grep out a list of all installed packages on a Debian or Ubuntu system.  This creates a file that you can use as a DR (disaster recovery) map of all installed apps -- you only need to install your base system, and then use this file to reinstall all of your apps.  Their earlier versions didn't produce a clean list and it certiainly didn't go the extra  mile of emailing a copy to you.

    
    dpkg -–get-selections | grep -v deinstall > ubuntu-files; cat ubuntu-files



    
    | mailx -s “ubuntu-files” <em>my.mail@my.address</em>
