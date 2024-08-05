---
title: "Run ChromiumOS on Dell Mini 9 with wifi"
slug: "howto-run-chromium-os-on-a-dell-mini-9-with-wifi"
date: "2010-01-12T03:48:23-06:00"
author: "fak3r"
categories:
- featured
- geek
- headline
- linux
tags:
- android
- Chrome OS
- Dell
- Dell Inc.
- Dell Inspiron Mini Series
- Embedded Linux
- google
- Google Chrome OS
- Google Inc.
- hexxeh.net site
- Intel
- Intel Corporation
- Moblin
- Netbooks
- ubuntu
---



**UPDATE 2: **it's 2012, and now I'm running the special Dell builds, with wifi (Hexxeh's builds (even Lime) don't support wifi on the Dell Mini 9 for me, even though the docs say it supports Broadcom BCM43xx chipsets), but there are docs to get Dell's custom builds rolling. The updated instructions are available on [kirsle.net](http://www.kirsle.net/blog/kirsle/install-chromium-os-lime) (thanks Kirsle!) Note that I also had the issue with the Dell April 15 build not booting after install without the USB drive, but there was a solution in the comments pointing to [this page](http://www.mydellmini.com/forum/google-chrome-os/28131-nice-new-chromeos-dell-4-15-2012-install-solid-state-drive-issue.html#post195917) on My Dell Mini and now it's all good. It's so nice to have this working again, looks like now it's going to be my laptop for next week's DEF CON 20!

**UPDATE**: I'm now running the latest build of Hexxeh's [Chrome OS named Flow](http://chromeos.hexxeh.net/) - and everything just works out of the box.  The release is much improved, and it's getting very close to being the perfect day-to-day netbook OS as far as I'm concerned.  Great work!

While I still really dig my [Dell Mini 9](http://www.dell.com/us/en/dfh/notebooks/laptop-inspiron-9/pd.aspx?refid=laptop-inspiron-9&cs=22&s=dfh), even with 2Gig of RAM it feels kinda sluggish when I have my normal 50 tabs open, and I've always known someone could do better (since I'm too lazy to recompile a kernel for it like I would have in the past).  With all the focus on netbooks it was bound to be addressed, and while [Android](http://www.android.com/) looks promising, it's currently still more of a phone OS than something you'd be able to use on your netbook.  I've run it off a USB drive on the Mini 9 just to check it out, it was cool, but again, not really usable enough for a 'top - maybe that's not the target. Another I want to check is [Moblin](http://moblin.org/), Intel's effort using Ubuntu as a base, but I haven't seen a Mini 9 HOWTO (maybe I'll have to write my own...) for that.  So, enter [Google Chrome OS](http://en.wikipedia.org/wiki/Google_Chrome_OS), Google's idea of how to not only address this problem, but perhaps lay out how we will use these computers in the future.  It's always funny when I start talking about cloud and thin clients, it takes me back to dumb terminals talking to mainframes, but I digress. The point is, thanks to great posts at [jasongriffey.net](http://www.jasongriffey.net/wp/2009/11/25/google-chrome-os-on-a-dell-mini9/) and [Lifehacker](http://lifehacker.com/5416968/the-humans-guide-to-running-google-chrome-os), it's really easy to install Google's Chrome OS on a Dell Mini 9, the only thing I really have to add is that you have to use [ChromeOS Zero](http://chromeos.hexxeh.net/) from the hexxeh.net site. After all, this is an open source project, so folks are going to make changes/fix things and share with everyone. Looking at the site they had a new release, yesterday (gotta love it!) The last time I tried a build the wifi on my Mini just worked, so it looks like those problems are a thing of the past.
