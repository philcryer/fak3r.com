---
author: phil
comments: true
date: 2008-07-17 08:39:02
layout: post
slug: howto-fix-fonts-in-debian-lennysid
title: 'HOWTO: fix fonts in Debian Lenny/Sid'
wordpress_id: 823
categories:
- geek
tags:
- autohinting
- debian
- desktop
- font
- fonts
- lenny
- sid
- ubuntu
---

![Fonts FTW](http://fak3r.com/wp-content/uploads/2008/07/font-bitmap.png)**UPDATE**: also, before you try this, make sure you have some good fonts installed, after a fresh install of Lenny at work, I needed to run this first: _apt-get install ttf-mscorefonts-installer msttcorefonts_

After a...slight slip up, I finally had the chance to install Linux from scratch on my laptop (Dell Vostro 1500) the way I've always wanted it with Debian GNU/Linux - Lenny and partitioned with LVM (Linux Volume Management).  After that I set out to get the desktop fonts to look as good in Debian as they did (by default) in Ubuntu.  After much scouring around online I found a pretty easy tweak that got me most of the way.  As root:

    
    dpkg-reconfigure fontconfig-config


In the dialog choose these options; Autohinter, Automatic and No.  Now issue this command:

    
    dpkg-reconfigure fontconfig


Logout and log back into your desktop and your fonts should be *noticeably* nicer looking.  Of course after that you have to drive yourself crazy tweaking the settings for autohinting and RGB lines, installing any and all ttf-* fonts apt-get sees, but hey, that's what choice is all about! ;)![Fonts](http://Fonts)
