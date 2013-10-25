---
author: phil
comments: true
date: 2011-01-31 13:08:52
layout: post
slug: howto-reclaim-your-linux-bootup-messages
title: HOWTO reclaim your Linux bootup messages
wordpress_id: 2772
categories:
- howto
- linux
tags:
- boot messages
- bootup
- down with icons
- grub
- icons
- linux
- menu.1st
- quiet
- rcS
- scrolling text
- splash
- text
---

[caption id="attachment_2775" align="alignright" width="210" caption="Ah, does it get any better than this?"][![](http://fak3r.com/files/2011/01/debian_arm_qemu_console-300x225.png)](http://fak3r.com/2011/01/31/howto-reclaim-your-linux-bootup-messages/debian_arm_qemu_console/)[/caption]

You know the drill, you bootup a [Linux](http://www.debian.org/) box and **watch the boot messages scroll by** on the screen, now prepended with lines telling you the seconds since boot, and then you end up at a shell prompt for login. Ahh, **the way Linus intended, epic!** Oh, you don't see that? Instead you see some animated Linux distro logo or something as useless like a progress bar tracing across the screen? Uggh, I hate that, you don't know what's really going on behind the scenes, and **if Linux is anything, it's transparent**. So, let's get that fixed for you. Basically as Linux as 'matured', we've been forced to load and watch more animated boot 'splash' screens for branding, and to make Linux more user-friendly, or more likely, more Windows or Mac-like. This way new users won't run for the hills if they see something like:

    
    [      2.125987 ] scsi 0:0:0:0: Direct-Access        ATA       ST380815AS       3.CH PQ: 0 ANSI: 5
    [      2.128065 ] sd 0:0:0:0: [sda] 156301488 512-byte logical blocks: (80.0 GB/74.5 GiB)
    [      2.128072 ] sd 0:0:0:0: Attached scsi generic sg0 type 0


Oh the horror, right? **WRONG!** If you're like me and you use Linux because you love it and want to know what it's doing all of the time, you can't get enough details like this. So, there are a few simple ways to reclaim the way  Linux shows the bootup details; because it's all still going on regardless of what eye-candy is hiding it. First, we want to tell Linux that we want to know what's going on, and not be given part of the picture. Let's edit /etc/default/rcS (in [Debian GNU/Linux](http://www.debian.org/) and [Ubuntu Linux](http://www.ubuntu.com/)) and see what it tells us:

    
    #
    # /etc/default/rcS
    #
    # Default settings for the scripts in /etc/rcS.d/
    #
    # For information about these variables see the rcS(5) manual page.
    #
    # This file belongs to the "initscripts" package.
    
    TMPTIME=0
    SULOGIN=no
    DELAYLOGIN=no
    UTC=yes
    VERBOSE=no
    FSCKFIX=no


What? Verbose=no? I don't think so. Let's edit that to read:

    
    VERBOSE=yes


Now we're getting somewhere! Next up, **we need to tell the bootloade**r, in this case [Grub](http://www.gnu.org/software/grub/), to** give us all the yummy text goodness that a growing body needs! **For this we edit /boot/grub/menu.1st, you'll want to scroll just past the line that reads:

    
    ## ## End Default Options ##


Now, look at the first block after that, mine says:

    
    title           Ubuntu 10.10, kernel 2.6.35-25-generic-pae
    uuid            53af9894-9fd1-45fe-b102-3ad0134eace3
    kernel         /boot/vmlinuz-2.6.35-25-generic-pae root=UUID=53af9894-9fd1-45fe-b102-3ad0134eace3 ro quiet splash
    initrd          /boot/initrd.img-2.6.35-25-generic-pae
    quiet


And that's the default kernel Linux is going to boot into if you don't choose anything. See the kernel line? We can already tell that the **commands like quiet and splash aren't going to get us what we need**, so we edit that to read something like:

    
    kernel         /boot/vmlinuz-2.6.35-25-generic-pae root=UUID=53af9894-9fd1-45fe-b102-3ad0134eace3 ro


Then, since it's Linux and we have full control over what's going on, we can tell it how we want the text rendered; the color depth and the screen resolution. Basically it breaks down like this:








Depth


800×600


1024×768


1152×864


1280×1024


1600×1200






8 bit


vga=771


vga=773


vga=353


vga=775


vga=796






16 bit


vga=788


vga=791


vga=355


vga=794


vga=798






24 bit


vga=789


vga=792




vga=795


vga=799




So, a simple way to set the screen resolution for text display during boot is by adding the vga= option to the end of that kernel line. For the value to append to it, it all depends on things like your graphics card, the display you're using, etc, but, most things can at least handle 1024x768 at 16 bit, so **vga=791 is a popular choice**. So, now the kernel line should say:

    
    kernel         /boot/vmlinuz-2.6.35-25-generic-pae root=UUID=53af9894-9fd1-45fe-b102-3ad0134eace3 ro vga=791


Simple enough, then look at the quiet line at the end of that block, I don't like the looks of that, so I'll comment it out for good measure:

    
    #quiet


Now you should quit and save and then issue the command 'reboot', or **if you want to wow them with how old skool you are**:

    
    /sbin/shutdown -r now


Ah, that's better :) Now after your machine comes on, the Grub menu will show up for a second, then you'll be dropped to the almighty text mode that you know you wanted all the time, so you can **see everything Linux is doing while it loads **and gets ready for work, just as it is supposed to be! Also, I once saw a scene similar to this when I was boarding a [Qantas](http://www.qantas.com.au/) flight in Australia when the onboard entertainment center was booting up, and it looked liked this, but aisle after aisle of it!

[caption id="attachment_2778" align="aligncenter" width="300" caption="This is your captain speaking, enjoy your flight, and Linux!"][![](http://fak3r.com/files/2011/01/3412037083_729a1fb6a9_o-300x221.jpg)](http://fak3r.com/2011/01/31/howto-reclaim-your-linux-bootup-messages/3412037083_729a1fb6a9_o/)[/caption]

So, what do you think, is it silly for me to wax nostalgic about such a low level thing as boot messages? Should I not worry that people learning Linux now might miss out on learning exactly what is happening? Do you have even more settings to get lower down? Great, spout off about them in the comments, but if I get criticized too much I'll break out some, "**When I was a kid, we didn't have a mouse**, keyboards were the only way to get work done, and don't get me started on those file managers..." and so on. ;)
