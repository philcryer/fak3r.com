---
title: "Transfer files via netcat and tar"
slug: "transfer-files-via-netcat-and-tar"
date: "2007-02-15T10:23:34-06:00"
author: "fak3r"
categories:
- geek
tags:
- hacker
- howto
---

![netcat - sm](http://fak3r.com/wp-content/uploads/2007/02/200px-netcat.png)[Netcat ](http://en.wikipedia.org/wiki/Netcat)(nc) is a "_...simple Unix utility which reads and writes data across network connections, using TCP or UDP protocol. It is designed to be a reliable back-end" tool that can be used directly or easily driven by other programs and scripts. At the same time, it is a feature-rich network debugging and exploration tool, since it can create almost any kind of connection you would need and has several interesting built-in capabilities._"  Basically it's another small, cool Unix tool that allows you to do tons of cool stuff.  I found this example out there that lets you transfer files via tar from one box to another.  As with anything to do with nc, it's dead simple, and logical.  On the target box, start nc to listen on a port, and tar up anything it 'hears' like this:

`nc -l -p $PORT | tar -xf -`

Then, on the source system, have tar pipe out to netcat, that is pointed to the target host/ip:

`tar -cf - $DIRECTORY | nc $HOST $PORT`

Damn, how cool.  There's plenty more info out there, and the more you look the more you'll realize what you can do with nc.   Tons of great info at the above Wikipedia link, and I also found a great overview at [Vulwatch.org](http://www.vulnwatch.org/netcat/readme.html).  Have fun!
