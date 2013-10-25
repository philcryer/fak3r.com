---
author: phil
comments: true
date: 2005-11-02 21:04:00
layout: post
slug: moving-boxes
title: Moving boxes
wordpress_id: 71
categories:
- geek
- rant
tags:
- hacker
---

So over the weekend I moved my servers with the idea to gain a bunch of floors space that we need since we're planning to build a room down there.  I didn't have much time to play with them, as I had a sick boy to take care of, but once he was down for a nap I headed down to the basement to move and reorganize my NOC (aka- a nice metal shelf with 4 computers on it) from the open area to a carved out nook under the stairs.  I had to take the shelf apart to wedge it in there, and lower some of the shelf heights, but it fits well.  Then I go to shut down the servers, move over the DSL line, DSL modem, the wireless router, the wireless print server and get them all up and running.  Then I move the main server (FreeBSD) and boot it up, then go to work on the old Linux server.  A few minutes later I see the old server with a prompt half way through boot; not good.  I take a look, superblock issue on one of the partitions; even worse.  So to debug some of the errors I jumped on #freebsd on irc.freenode.net on the iBook so I could better figure out how to fix this.  I remember one response, "Your box is toast" to which I replied, "The hell it is, I don't have time to rebuild a server today!”  After some manual monkeying around with fsck I was able to determine that /var wasn't mounting, and it was the one with the superblock issue.  Of all the partitions to go South, /var isn't really that bad.  I knew I wanted to take it out of my fstab so the server wouldn't try to mount it during boot, but I had no text editor that I knew of (nano, vi, etc were not there, or didn't have the libs loaded to run), so on to IRC I asked.  Finally dho walked me through some crazy command and I got ed to modify my fstab.  Reboot, it doesn't mount the bad drive, bitches allot about missing files since there is no /var, but who can blame it?  I got in, created a new /var on /, then symlinked it to the backup drive that also lives on that server under /mnt.  Then since I have nightly rsyncs of all computers on the network, I just copied the backup of the server's /var from the previous night on the symlinked dir, rebooted, and what do ya know it all "just worked".  I was pretty proud of myself; I need to get a job doing this...
