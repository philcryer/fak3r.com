---
author: phil
comments: true
date: 2008-08-01 08:48:47
layout: post
slug: howto-convert-an-avi-or-to-dvd-vob-in-linux
title: 'HOWTO: convert an AVI or  to DVD (VOB) in Linux'
wordpress_id: 849
categories:
- geek
- howto
- movies
- music
tags:
- avi
- convert
- debian
- dvd
- dvd player
- gnome
- vob
---

[![](http://www.fak3r.com/wp-content/uploads/2008/09/dvd_icon.jpg)](http://www.fak3r.com/wp-content/uploads/2008/09/dvd_icon.jpg)I have some AVIs that I needed to get into the VOB format so I could burn them to DVD.  I knew I could do this in Linux, but didn't know how.  Here is how I did it with Debian GNU/Linux (testing - Lenny).  First I installed the GTK+ app, [Avidemux](http://fixounet.free.fr/avidemux/) (don't worry, we'll get back to the commandline soon).  The I opened the AVI in Avidemux - after it imported it I clicked on FILE -> SAVE -> SAVE VIDEO - then choose where to save the file.  I saved it as movie.mpg so it would work with my next step.  This took some time for me, even with my duo-core 1.6Mhz 1Gig RAM laptop, but once it was complete I could play the mpg file in multimedia apps, so it worked.  Now we need to make it into the format that you can burn to DVD.  These are the weird looking folders named VIDEO_TS and AUDIO_TS that you'll burn to the root of the DVD.  To build this from an mpg is pretty easy, you need to install the commandline app [dvdauthor](http://dvdauthor.sourceforge.net/).  Once this is downloaded we need a simple XML file to tell dvdauthor what to do, so I created a base/simple one - open your text editor of choice (vim ftw!) and create a new file:

    
    vi dvdauthor.xml


with the contents (NOTE: change all ( and )s to brackets, apparently neither the pre or code tag accepts brackets in them in wordpress - reminder to self, fix this):

    
    (dvdauthor dest="DVD")
      (vmgm /)
       (titleset)
         (titles)
           (pgc)
             (vob file="movie.mpg" chapters="0,15:00,30:00,45:00,1:00:00"/)
           (/pgc)
          (/titles)
       (/titleset)


Now run dvdauthor referring to new XML file:

    
    dvdauthor -x dvdauthor.xml


When it's done you'll have a new directory called DVD, with the contents VIDEO_TS and AUDIO_TS.  Open your favorite DVD burning app (I recommend [GnomeBaker](http://sourceforge.net/projects/gnomebaker)) and place those two direcotries in the root of the DVD and burn.  Notice that in the XML file we called out chapters as 0, 15, 30, 45, 1 hour - obviously these can be further tweaked to be 'real' chapters, or left out all together.  I'm sure there's a GUI DVD authoring app that helps you do this, if you figure it out post below and let me know.  HTH!
