---
author: phil
comments: true
date: 2005-10-08 16:08:00
layout: post
slug: zimbra-build-script-released
title: Zimbra build script released
wordpress_id: 83
categories:
- geek
tags:
- code
---

Here's my first swing at a hacked together [build script](http://cryer.us/phil/code/zimbra/) to download, build, compile and install the [Zimbra Collaboration Suite](http://www.zimbra.com/). If you haven't been hip to it, Zimbra just kinda appeared out of nowhere, with a pretty nice email/cal webapp that has all the AJAX goodness you could hope for, with true drag and drop, pop up balloons, live searching and more. They have a demo you can play with [here](http://demo.zimbra.com/ZimbraDemo/ValidateDemoCreation.jsp?username=d1128722679.592254@zimbra.com&mailto=d1128722679.592254@zimbra.com&hash=3a4e41e03e300651ffa4b9a2ba3d5ab1), and it's worth checking out, just to see what's up. For now my focus for day to day is still on the [Hula Project](http://www.hula-project.org/Hula_Server), but to play with Zimbra I needed to hack a script together just so I could get this built on a 'normal' Linux distro (in this case my old server, jorge running Gentoo), and not Red Hat Enterprise and Fedora as they had released. I've already sent the script to a Zimbra dev, as well as a Ubuntu hacker for testing, so we'll go from there. First impressions are that it's a really nice, all in one email/cal server/app presentation. Unlike Hula controling everything, Zimbra is more of a LAMP application, so it will be interesting to see where they place this in terms of how well it could scale in an enterprise environment.

