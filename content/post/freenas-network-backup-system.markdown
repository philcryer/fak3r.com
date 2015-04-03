---
title: "FreeNAS: network backup system"
slug: "freenas-network-backup-system"
date: "2007-01-24T09:59:08-06:00"
author: "fak3r"
categories:
- geek
tags:
- bsd
---

![FreeNAS](http://fak3r.com/wp-content/uploads/2007/01/freenas.gif)Yesterday NewsForge had an excellent article called, "[_A look at the FreeNAS server_](http://hardware.newsforge.com/article.pl?sid=06/05/19/1349206&from=rss)". Basically [FreeNAS](http://www.freenas.org/) is a small operating system based on FreeBSD 6 that provides NAS, or network-attached storage, ([Wikipedia page](http://en.wikipedia.org/wiki/Network-attached_storage)) services like NFS (Network File System), CIFS (Microsoft's Common Internet File System aka Samba) as well as tried and true Unix utilities like ftp, rsync, unison, ssh, scp, etc. The short explanation, this will take an old/unused PC and turn it into a true network accessible backup system that all of my home clients (Linux, Mac OS X and Windows) can talk to. My current backup strategy consists of my FreeBSD server running RAID1 to provide mirroring for redundancy over two drives that the clients rsync over ssh to. A standalone solution would be a better option as it would backup everything to the NAS, which I would also run in a RAID1 mirrored mode, giving the same amount of protection to all of the client data, but doubling up the backup of the server (server has it's data already mirrored over two drives, plus the data copied to the NAS is mirrored over two drives for a total of four copies). There are plenty of cool features of FreeNAS including the fact that since it's FreeBSD based it supports the same gmirror setup I've worked with before for RAID, the whole thing is bootable from a USB drive, compact flash (since the whole OS weighs in at only 32Meg!), or a regular harddrive, all of the administration can be done via the WebGUI, and the base distro is based off the one used for [m0n0wall](http://m0n0.ch/wall/), a similar project that just handles firewall duties. So they've made a smart move using an existing framework, and then just building the backup control the web employs. The project is under active development, and looks like a winner for anyone needing a network backup system for home to small office. I have an old machine picked out at home, I need to find two drives for the storage and then I'll build this out. Shouldn't take long at all, and will give my USB drive something to do besides hang on my keychain! Their [install docs](http://www.freenas.org/downloads/docs/user-docs/FreeNAS-SUG.pdf) seem pretty complete...stay tuned.

