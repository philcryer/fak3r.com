---
author: fak3r
comments: true
date: 2012-08-12 16:45:00
layout: post
slug: howto-update-openelec-on-raspberry-pi
title: HOWTO update OpenELEC on Raspberry Pi
wordpress_id: 3957
categories:
- geek
- howto
- linux
- movies
- music
- tv
- video
tags:
- embedded
- linux
- openelec
- raspberry pi
- xbmc
---

[![OpenELEC](http://fak3r.com/assets/OpenELEC-logo-300x73.png)](http://fak3r.com/2012/08/12/howto-update-openelec-on-raspberry-pi/openelec-logo/) OpenELEC (Embedded Linux Entertainment Center)

One of my goals after getting my [Raspberry Pi](http://www.raspberrypi.org/) was to use it as an [XBMC](http://xbmc.org/) server. XBMC is an open source (GPL) software media player and entertainment hub for digital media. It runs on Linux, OSX, and Windows, and works great as a set top media center, ala something like [Apple TV](http://www.apple.com/appletv/). Meanwhile another effort called [OpenELEC](http://openelec.tv/) is like a live build of XBMC that you can install on embedded hardware that has been built from scratch specifically to act as a media center, stripped down to the very most basic essentials for a light installation with a quick boot time. Even better, they have builds that are [specifically tailored](http://openelec.tv/get-openelec) to the very-specific hardware like Ion, Fusion, Intel, and... wait for it... beta versions for Raspberry Pi! So my mind was made up, getting OpenELEC up and running on the Pi was a simple affair with their excellent [docs on their wiki](http://wiki.openelec.tv/index.php?title=Installing_OpenELEC_on_Raspberry_Pi). This worked as advertised and things were good. Of course, since this is an early development version, there are lots of changes and improvements as time goes by, and updating is necessary. While you can go through the entire install again, it's not necessary, and some quick poking around on their forums turned up a simple [update script](http://openelec.tv/forum/133-installation/42383-update-script-for-openelec-on-raspberry-pi#43081) that I downloaded to the Pi and ran, and it worked - it updated to the latest build of OpenELEC and rebooted to install it. This is where things went south, black screen city! Luckily the OS was running so I could still SSH in and poke around, sure enough, everything looked fine, no errors, etc, but it just wasn't working. My solution was to downgrade to an older version (that was still months newer than the original install I was using), but how could I automate it? I did it by modifying the update script to get a list of available builds and prompt you to choose what build to install. I ran this, rolled back to an earlier build, and boom, everything worked again! I have the script on [Github as a gist](https://gist.github.com/3333039), embedded here for connivence. As always feedback is welcome.

<!-- more -->


