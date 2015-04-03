---
title: "HOWTO: conky config (conkyrc) for Debian Part 2"
slug: "howto-conky-config-conkyrc-for-debian-part-2"
date: "2008-08-26T16:45:50-06:00"
author: "fak3r"
categories:
- geek
tags:
- config
- conky
- conky config
- conkyrc
- debian
- howto
- lightweight desktop
- linux
- openbox
---

[![](http://www.fak3r.com/wp-content/uploads/2008/09/conky-unix-program-screen-shot.png)](http://www.fak3r.com/wp-content/uploads/2008/09/conky-unix-program-screen-shot.png)I changed around my [Conky](http://conky.sourceforge.net/) config, and it's something you could do forever, but it's great because it can be as heavy or light as you want it.  Recently I dropped Gnome almost all together to run Openbox (full HOWTO on this forthcoming).  I found a panel that will house things like nm-applet output, but was missing things like a simple clock, network activity, etc.  So now, using most of the same code/look that I used [here](http://fak3r.com/2008/07/01/howto-conky-config-conkyrc-for-debian/), I have a small, transparent strip at the bottom of the screen showing me time, date, proc, proc temp, network up, network down, and power status (battery, AC and the level of charge).  It looks good, it's light, it's all I need.  Nice to bring some of the memory requirements down from Gnome as well.


> # Create own window instead of using desktop (required in nautilus)
own_window true
own_window_hints undecorated,below,skip_taskbar
background no
# Use double buffering (reduces flicker, may not work for everyone)
double_buffer true
# fiddle with window
use_spacer right
use_xft true
# Update interval in seconds
update_interval 3.0
# Minimum size of text area
minimum_size 10000 5
# Draw shades?
draw_shades yes
# Text stuff
draw_outline no # amplifies text if yes
draw_borders no
uppercase no # set to yes if you want all text to be in uppercase
# Stippled borders?
stippled_borders 8

# border margins
border_margin 1
# border width
border_width 1
# Default colors and also border colors, grey90 == #e5e5e5
default_color white
default_shade_color black
default_outline_color white
own_window_colour brown
own_window_transparent yes
# Text alignment, other possible values are commented
#alignment top_left
#alignment top_right
alignment bottom_left
#alignment bottom_right
# Gap between borders of screen and text
gap_x 10
gap_y 5
# stuff after 'TEXT' will be formatted on screen
override_utf8_locale no
#xftfont Terminus:size=8
xftfont Terminus:size=10
xftalpha 0.8
#Mail:${color}${execi 300 python ~/scripts/gmail.py}
TEXT

${offset 0}${color }${time %H:%M} ${color slate grey}${time %Z    }Date: ${color }${time %a, } ${time %e %B %G} ${offset 0} ${offset 0}   ${color slate grey}Proc:${color} $cpu%${offset 5}${acpitemp}C${offset 5}${cpugraph 16,100 000000 ffffff} ${offset 0}   ${color slate grey}Net:${offset 5}${color}Up:${upspeed wlan0}k/s${offset 5}${upspeedgraph wlan0 16,100 000000 ffffff}${offset 0}   ${color}Dn:${downspeed wlan0}k/s${color}${offset 5}${downspeedgraph wlan0 16,100 000000 ffffff}   ${color slate grey}    Power:${offset 5}${color}${battery}



Try it, you might like it - I'll keep working on it, I'm sure I'll find more things to add/improve.  Conky rocks.
