---
title: "HOWTO: Use a file list in Ubuntu for quick system restore"
slug: "howto-use-a-file-list-in-ubuntu-for-quick-system-restore"
date: "2006-09-20T09:43:19-06:00"
author: "fak3r"
categories:
- howto
- linux
tags:
- howto
---

While I've read this plenty of times, today via Digg I [found complete docs](http://www.arsgeek.com/?p=564) that I wanted to save on how to restore a Ubuntu Linux install; bringing it back to the way you had it from a fresh install easily.  Why would you need this?  Well, hard drives die, but more often (in my case at least) it's *fun* to start with a fresh system when new versions of Ubuntu come out, or when you *have* to try out the latest/fastest filesystem, or you can't live without the latest/bleeding edge apps/features.  So to start, you'll first need a snapshot of your installed applications on your working system, which is easy enough to do:

    
    dpkg –-get-selections | grep -v deinstall > ubuntu-files


After this you could copy the file ubuntu-files to a USB thumbdrive, and while this would work, let's go for some x-tra credit and have this created file emailed out for easy remote storage:

    
    dpkg –-get-selections | grep -v deinstall > ubuntu-files;



    
    cat ubuntu-files | mailx -s "ubuntu-files" fak3r@fak3r.com


When you reinstall the next time just do a quick base install of Ubuntu, which takes all of 15 minutes on today's average machines, then drop to the cmd line, grab your ubuntu-files file and then run the following:

    
    sudo apt-get update
    sudo apt-get dist-upgrade
    dpkg –set-selections < ubuntu-files


Now you're back to the point of your last snapshot with all the kewl apps you installed that broke things in the first place!  Oh, wait...
