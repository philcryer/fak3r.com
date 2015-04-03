---
title: "HOWTO use ionice to control long running jobs"
slug: "howto-use-ionice-to-control-long-running-jobs"
date: "2012-04-03T22:21:17-06:00"
author: "fak3r"
categories:
- geek
- howto
- linux
tags:
- cron
- cronjobs
- ionice
- sysadmin
---

If you have a long running process (rsync, cp, find updatedb, etc) that causes a high load on your Linux system, it's likely going to cause a problem (and unneeded Nagios alerts!) when it's run via cron. This was happening on a server of mine that backed up the Apache Solr indexes every night, so after searching around I found a utility to handle this situation, ionice. This works much like the venerable nice command, but focuses on I/O priority instead of processor priority. Of course since I wasn't going to be around to turn this on I needed a way for it to work in a script, and it ended up being pretty simple. In the head of your BASH script, turn it on a set it for -p$$ which is the working shell, so it will be in effect for anything within that script. Here's a simple example, again, just turn it on before any long running commands, or anything that you want to turn down the I/O priority for
<!-- more -->

    
    #!/bin/bash
    set -e
    ionice -c3 -p$$
    
    ### rest of script ###
    
    exit 0


So this is another trick that will end up in most of my scripts now, it's simple, does what it says, and doesn't cause Nagios to shoot off emails about its activity! Did this work for you? Do you know a better way to do this? Sound off below, thanks!
