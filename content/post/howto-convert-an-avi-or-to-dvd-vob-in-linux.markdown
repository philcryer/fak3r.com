---
title: "HOWTO: convert an AVI or  to DVD (VOB) in Linux"
slug: "howto-convert-an-avi-or-to-dvd-vob-in-linux"
date: "2008-08-01T08:48:47-06:00"
author: "fak3r"
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

[ and create a new file:

    
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
