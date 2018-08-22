---
title: "HOWTO: automatically reconfigure Xorg in Debian"
slug: "howto-automatically-reconfigure-xorg-in-debian"
date: "2008-05-27T09:38:58-06:00"
author: "fak3r"
categories:
- geek
- linux
tags:
- debian
- linux
- resolution
- screen
- ubuntu
- xorg
---

If you're like me, you've messed up your xorg.conf before and wanted to start over with the default that you know dpkg-reconfigure can set it to.  Because of this I'm posting here because I've needed it multiple times in the past and have tired of looking it up!  To automatically reconfigure Xorg in Debian or Ubuntu issue the following:

    
    sudo dpkg-reconfigure -phigh xserver-xorg


Then logout/login or restart X via contrl-alt-backspace.  As one who tweaks things a bit more than he should, this has saved me a few times now.  Props go to a poster on [this page](http://www.cyberciti.biz/faq/ubuntu-linux-how-to-reconfigure-x-windows-system-xorg-server/).
