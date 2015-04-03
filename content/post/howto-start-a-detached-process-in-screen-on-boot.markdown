---
title: "HOWTO start a detached process in screen on boot"
slug: "howto-start-a-detached-process-in-screen-on-boot"
date: "2011-04-25T09:43:22-06:00"
author: "fak3r"
categories:
- howto
- linux
tags:
- background process
- bash
- detached process
- gnu screen
- linux
- screen
- start on boot
---

[caption id="attachment_2936" align="alignright" width="221" caption="Using a key to gouge expletives on another's vehicle is a sign of trust... and friendship."][![Using a key to gouge expletives on another's vehicle is a sign of trust... and friendship.](http://fak3r.com/wp-content/blogs.dir/12/files/ignignokt.png)](http://fak3r.com/geek/howto/howto-start-a-detached-process-in-screen-on-boot/attachment/ignignokt/)[/caption]

Ok, a quick one today - at work I had the problem of needing a process to be automatically started during boot, and have it running in the background, but it didn't have its own init.d script. I knew there was a way I could use [GNU Screen](https://www.gnu.org/software/screen/) (one of my favorite 'must have' sys admin tools) to do this, but it took me some time searching to find the right syntax to translate for my needs, so I'm posting it here.




`﻿su - phil -c "/usr/bin/screen -dmS solr /opt/start_solr141.sh"`








Let's look at what's happening here, first we use `su` to run this as a different user, then we define the user (phil), then in the command, called out by the -c and encased in double quotes, using the screen command with -smS to start a new detached session, giving it a name, and finally giving it a command to run in that detached screen session. Now put this line in something like /etc/rc.local, and it will run that command, in a detached screen session, automatically on boot. In this case a shell script to kick off the [Apache Solr](http://lucene.apache.org/solr/) process in a detached screen session that you can attach to later. Yes, I could have written a 'real' start-up script and made it into an init.d file, and I know you can run Solr under [Tomcat](https://tomcat.apache.org/), and I do, on production, but with test and development versions it's nice to be able to just spin new ones up quickly like this. Plus you can use the above line to start anything in a screen at boot, which is all I wanted to show in this example. Thanks!






