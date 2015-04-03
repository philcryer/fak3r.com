---
title: "Deprecated proc and C debugging"
slug: "deprecated-proc-and-c-debugging"
date: "2005-09-26T15:13:00-06:00"
author: "fak3r"
categories:
- geek
tags:
- code
---

While trying to debug Hula on FreeBSD I found that the normal GNU C debugging tools (gdb, truss, ktrace) fail since /proc is no longer on the filesystem, in FreeBSD 6.0, for them to write to.  It was deprecated as a security concern and functionality moved to sysctl for 5.x, but for 6.x it's just gone.  I'm looking for a long term solution, but short term was just to recreate /proc on the server and mount it.  One liner coming up:




    
    <code>echo "proc /proc procfs rw 0 0" >> /etc/fstab; mount /proc</code>





ince Linux still uses /proc I assume the functionality of sysctl would cover these tools to debug C code somehow and redirect stdin, but I don't know how.  Any FreeBSD C hackers out there with a hint?
