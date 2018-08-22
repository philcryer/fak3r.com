---
title: "HOWTO: send commandline email with attachments"
slug: "howto-send-commandline-email-with-attachments"
date: "2008-09-17T07:36:55-06:00"
author: "fak3r"
categories:
- geek
- howto
- humor
tags:
- bash
- commandline
- cron
- email attachment
- email commandline
- mailx
- uuencode
---

[


Are you like me, do you have scripts running on servers and you need to know what they know?  If there's output in a file you can sed/grep/awk info out of them and have them emailed to you, but if you don't know specifically what you're looking for you may need the entire file/log/whatever.  You'll need a utility called [uuencode](http://www.ss64.com/bash/uuencode.html), which is a utility that,<!-- more --> "..._writes an encoded version of the named input file, or standard input if no file is specified, to standard output. The output is encoded using the algorithm described in the STDOUT section and includes the file access permission bits (in chmod octal or symbolic notation) of the input file and the decode_pathname, for re-creation of the file on another system that conforms to this specification._"  All this means of course is the standard computer method; garbage in, garbage out.  Whatever you have uuencode encode, it will decode the same way, but while it's in the middle state it can be sent via email as an attachment.  It's pretty easy, first you need uuencode, Debian/Ubuntu users can install it as part of the 'sharutils' group of Unix utilities with `apt-get install sharurtils'.  Got it?  Ok, so all you need to do is point it to a file, pipe the resulting stream to mailx, give it a subject and destination email and you're done.

[code]uuencode syslog.log syslog.log | mailx -s &amp;amp;amp;quot;[`hostname`] Syslog update&amp;amp;amp;quot; fak3r@fak3r.com[/code]

So lock that away until you need it, and then use it as a one liner in cron for all your updating needs and wants.
