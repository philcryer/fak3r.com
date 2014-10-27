---
author: phil
comments: true
date: 2005-12-07 19:05:00
layout: post
slug: firefox-buffer-overflow
title: Firefox buffer overflow
wordpress_id: 59
categories:
- General
tags:
- code
- hacker
- security
---

There's a [Firefox buffer overflow](http://packetstormsecurity.org/0512-exploits/firefox-1.5-buffer-overflow.txt) script listed on Packet Storm.  The Javascript can be embedded into HTML and make Firefox log a very long topic line into its history.dat file.  Any ensuing Firefox starts will cause a crash due to a buffer overflow.  The fix would be to delete the history.dat file, which would be recreated automatically during the next start, but that's not something most users would know.  I'm sure this will be patched quickly, but this has to be the first type of bug I've seen targeting Firefox.




    
    <code>function ex() {
    var buffer = "";
    for (var i = 0; i < 5000; i++) {
    buffer += "A";
    }
    var buffer2 = buffer;
    for (i = 0; i < 500; i++) {
    buffer2 += buffer;
    }
    document.title = buffer2;
    }</code>



