---
author: phil
comments: true
date: 2010-01-12 20:51:44
layout: post
slug: howto-securely-delete-files-in-os-x-on-the-commandline
title: HOWTO securely delete files in OS X on the commandline
wordpress_id: 1968
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

[![](http://fak3r.com/wp-content/uploads/2010/01/565549820.png)](http://fak3r.com/wp-content/uploads/2010/01/565549820.png)So I've had my [MacBook Pro](www.apple.com/macbookpro) for a few months now, and since I have a 500 Gig harddrive, I haven't bothered to empty my trash yet. I'm currently on a work trip in China, and it makes me think about the privacy (internet and otherwise) that I have in the US, that I don't expect here. In fact, since we're blocked from posting to either [Facebook](http://facebook.com/) or [Twitter](http://twitter.com/), I know this post will only make it there because this site will post if for me after I post it to my site (again, not something you'd think about just living in most other parts of the world). So what a good time to learn how to securely emptying my trash! The first thing I did was use the 'Secure delete' feature of the [OS X](http://www.apple.com/macosx) trash folder, but with over 190,000 files to remove, it sat there at 0% while the fan spun up for about 15 minutes. That was it for me, it was clear it was going to take years for this to happen, so canceled that and hit [Google](http://google.com) to learn the right way to do it via the commandline. One of the [best pages](http://exxamine.wordpress.com/2007/08/16/secure-file-delete-on-mac-os-x/) talks about [srm](http://srm.sourceforge.net/) a secure file deletion for posix systems that is installed by default on OS X. I've crafted my `srm` command to use the `nice` command to reduce the amount of overhead the process causes (again, the GUI version was taking over the system and heating things up quickly) and the `sudo` command to ensure all files would be deleted regardless of permission/ownership. In the end in looks like this:

`sudo nice -19 srm -rfv ~/.Trash/*`

Yeah, while the `-v` flag will slow things down slightly, I prefer to have 'verbose' output from the command to understand exactly what it's doing. Does anyone have better/more secure way to do this? Leave a message in the comments if you do, I'd love to learn more about this.
