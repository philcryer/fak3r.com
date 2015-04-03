---
title: "HOWTO: failed to set xfermode [SOLVED]"
slug: "failed-to-set-xfermode-solved"
date: "2007-06-22T19:17:38-06:00"
author: "fak3r"
categories:
- geek
- howto
- linux
tags:
- hacker
- howto
---

**![Ubuntu logo](http://fak3r.com/wp-content/uploads/2007/06/ubuntulogo.png)UPDATE**: thanks to a comment below from [Ted](http://1isa2isb.com/), we now have a solution to have this option persist across kernel updates.  In grub, "..._at the end of this new menu item add it as an argument to the line_:


    
    defoptions=quiet splash irqpoll



I knew there had to be a way, thanks for the post Ted!

There's a known bug in Ubuntu 7.04 (Feisty) with some ata detection routine that causes the system to take over 2 minutes to boot. Since this has happened to me more than once I'm documenting it here for me, and for other desperate souls that may find their way here. If your system is very slow to boot, and you see error messages in your dmesg (`dmesg | grep ata`) such as this:

    
    [ 34.122465] ata1.00: qc timeout (cmd 0xef)
    [ 34.122519] ata1.00: failed to set xfermode (err_mask=0x4)
    [ 34.122565] ata1: failed to recover some devices, retrying in 5 secs
    [ 46.260055] ata1: port is slow to respond, please be patient (Status 0x90)
    [ 69.218482] ata1: port failed to respond (30 secs, Status 0x90)





You just need to ad `irqpoll` to your grub line. So in so in /boot/grub/menu.lst I added irqpoll to the kernel line:

    
    kernel /boot/vmlinuz-2.6.20-15-generic root=UUID=48c5a348-eb39-4171-8531-671a49fdb75b ro quiet splash irqpoll


and it fixes the issue. Probably a work around, but since this resets every time you install a new kernel you'll realize when it's broken and when it's fixed. Oh, and my system boots in 21 seconds now...is it geeky that I know that, and I tweaked the system to make it boot faster than the 27 seconds it was booting in? I guess we'll never know! ;)

