---
title: "HOWTO: usable xterm configuration"
slug: "howto-usable-xterm-configuration"
date: "2006-02-23T15:09:00-06:00"
author: "fak3r"
categories:
- geek
- linux
tags:
- bsd
- howto
---

**UPDATE**: if you’re using xterm in place of gnome-terminal due to speed, you aren’t any longer.  The 2.14 version of Gnome sports a [much faster gnome-terminal](http://www.gnome.org/start/2.14/notes/en/rnusers.html); it beats xterm for display by allot, and log startup is 20x faster than before!  Wow, they did some work on tuning there!


Ok, this is a cheap HOWTO seeing as how I just found it, cut/pasted it and restarted X - but hey, it rocks.  I’ve always used Gnome-terminal when in [Gnome](http://gnome.org/), but I also tend to when I’m in [Openbox](http://icculus.org/openbox/) or [Xfce](http://www.xfce.org/) I use it since I can control the fonts to make it usable, unlike xterm.  Last night after installing Xfce4 (recommended) on my iBook I hit their FAQ to do some tweaking where I came across [this](http://www.xfce.org/various/Xresources.txt), and now I can use xterm!  It’s right in time to since Gnome-terminal loads very slowly when you’re not in Gnome, and that’s especially annoying when you’re playing in a ‘lighter’ window manager/desktop environment.  Xterm, on the other hand, just pops up like it was waiting for you.  So, to try out this config yourself, hit [this page](http://www.xfce.org/various/Xresources.txt) on their documentaion page, or read more on this post for the quick HOWTO and code.




Edit or create an .Xsessions file in your user’s home directory:






    
    <code>vi ~/.Xsessions</code>







Copy code from the URL above, or the code below and paste it in there.  Now save:






    
    <code>:wq!</code>







Have xrdb source the .Xresources file:






    
    <code>xrdb -merge .Xresources</code>







and finally, launch xterm:






    
    <code>xterm</code>







That’s it!  Now xterm will look like that anytime you launch it, and this even works in Windows when using Cgywin, same steps as above.  Sure as heck beats Cgywin’s shell…don’t get me wrong, I appreciate Cgywin, but installing X just to get a useable term is a necessary when you’re forced to use Windows.




Here is the code to copy:






    
    <code>! this are Xresources to make xterm look good
    ! put into ~/.Xresources
    ! after changing contents, run xrdb -merge .Xresources
    ! gentoo has a bug so that it doesnt read it when X starts, so add above
    ! command to /etc/xfce4/xinitrc (top) and be happy.
    
    !xterm*background:  Black
    !xterm*foreground:  Grey
    xterm*font:     -Misc-Fixed-Medium-R-Normal--20-200-75-75-C-100-ISO10646-1
    !xterm*font:        -misc-fixed-medium-r-normal--18-*-*-*-*-*-iso10646-1
    !xterm*iconPixmap: ...
    xterm*iconPixmap:       /usr/share/pixmaps/gnome-gemvt.xbm
    xterm*iconMask:         /usr/share/pixmaps/gnome-gemvt-mask.xbm
    !XTerm*iconName: terminal
    !Mwm*xterm*iconImage: /home/a/a1111aa/xterm.icon
    XTerm*loginShell: true
    XTerm*foreground: gray90
    XTerm*background: black
    XTerm*cursorColor: rgb:00/80/00
    XTerm*borderColor: white
    XTerm*scrollColor: black
    XTerm*visualBell: true
    XTerm*saveLines: 1000
    !! XTerm.VT100.allowSendEvents: True
    XTerm*allowSendEvents: True
    XTerm*sessionMgt: false
    !XTerm*eightBitInput:  false
    !XTerm*metaSendsEscape: true
    !XTerm*internalBorder:  10
    !XTerm*highlightSelection:  true
    !XTerm*VT100*colorBDMode:  on
    !XTerm*VT100*colorBD:  blue
    !XTerm.VT100.eightBitOutput:  true
    !XTerm.VT100.titeInhibit:  false
    XTerm*color0: black
    XTerm*color1: red3
    XTerm*color2: green3
    XTerm*color3: yellow3
    XTerm*color4: DodgerBlue1
    XTerm*color5: magenta3
    XTerm*color6: cyan3
    XTerm*color7: gray90
    XTerm*color8: gray50
    XTerm*color9: red
    XTerm*color10: green
    XTerm*color11: yellow
    XTerm*color12: blue
    XTerm*color13: magenta
    XTerm*color14: cyan
    XTerm*color15: white
    XTerm*colorUL: yellow
    XTerm*colorBD: white
    !XTerm*mainMenu*backgroundPixmap:     gradient:vertical?dimension=400&start=gray10&end=gray40
    !XTerm*mainMenu*foreground:          white
    !XTerm*vtMenu*backgroundPixmap:       gradient:vertical?dimension=550&start=gray10&end=gray40
    !XTerm*vtMenu*foreground:             white
    !XTerm*fontMenu*backgroundPixmap:     gradient:vertical?dimension=300&start=gray10&end=gray40
    !XTerm*fontMenu*foreground:           white
    !XTerm*tekMenu*backgroundPixmap:      gradient:vertical?dimension=300&start=gray10&end=gray40
    !XTerm*tekMenu*foreground:            white
    !XTerm Profiles (idea from dag wieers)
    XTerm*rightScrollBar: true</code>



