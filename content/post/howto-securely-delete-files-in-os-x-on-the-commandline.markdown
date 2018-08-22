---
title: "HOWTO securely delete files in OS X on the commandline"
slug: "howto-securely-delete-files-in-os-x-on-the-commandline"
date: "2010-01-12T20:51:44-06:00"
author: "fak3r"
categories:
- geek
- howto
- travel
tags:
- China
- command-line
- Facebook
- google
- Mac OS X
- POSIX
- posix systems
- Srm
- Sudo
- twitter
- United States
---

[ and the `sudo` command to ensure all files would be deleted regardless of permission/ownership. In the end in looks like this:

`sudo nice -19 srm -rfv ~/.Trash/*`

Yeah, while the `-v` flag will slow things down slightly, I prefer to have 'verbose' output from the command to understand exactly what it's doing. Does anyone have better/more secure way to do this? Leave a message in the comments if you do, I'd love to learn more about this.
