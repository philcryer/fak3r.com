---
title: "HOWTO: sound after hibernate in Linux (Gusty/Lenny)"
slug: "howto-sound-after-hibernate-in-linux-gustylenny"
date: "2008-02-25T11:51:31-06:00"
author: "fak3r"
categories:
- geek
- howto
tags:
- debian
- hibernate
- lenny
- linux
- sound
- ubuntu linux
- vostro 1500
---

![Ignignokt says - Using a key to gouge expletives on another’s vehicle is a sign of trust and friendship](http://www.fak3r.com/wp-content/uploads/2008/02/ignignokt2.thumbnail.gif)With all the tweaking to get my [Dell Vostro 1500](http://www.fak3r.com/2007/10/10/buying-a-linux-laptop-in-2007/) working with Ubuntu, it's still been an annoyance to get sound working evertime after hibernation.  It goes to sleep fine, it wakes up fine, it obeys all of the power preferences I defined within Gnome fine too, it's just that when it comes out of hibernation, the sound is usually off.  It's not muted, it's off.  Trying to restart alsa (the sound server) is a lession in frustration, so until now I've been ignoring it since it was rare that I would need it, but still...come on.  This week I came across [a solution in the Debian Forums](http://forums.debian.net/viewtopic.php?t=21808&highlight=vostro+1500) that creates a new task for Linux to do before it shuts down and before it starts up.: "_Create the file /etc/pm/sleep.d/49sound..._

    
    mkdir /etc/pm
    vi /etc/pm/sleep.d49sound


with the following contents:

    
    function kill_sound_apps() {
    pidsnd=$(lsof | grep /dev/snd | awk '{ print $2 }')
    pidmixer=$(lsof | grep /dev/mixer | awk '{ print $2 }')
    piddsp=$(lsof | grep /dev/dsp | awk '{ print $2 }')
    kill $pidsnd $pidmixer $piddsp
    }
    
    case "$1" in
    hibernate|suspend)
    kill_sound_apps
    modprobe -r snd_hda_intel
    ;;
    thaw|resume)
    modprobe snd_hda_intel
    ;;
    *)
    ;;
    esac
    
    exit $?


Then just make it executable:

    
    # chmod +x /etc/pm/sleep.d/49sound


So before shutting down, Linux properly shuts down the sound, and when it comes back it, it properly starts the sound.  As always, this *should* work, but the fact that it hasn't been updated in Ubuntu Gusty is one of the reasons I'm shifting to Debian Lenny on this 'top.

NOTE: yes, I am thinking of making [Ignignokt ](http://www.adultswim.com/shows/athf/stuff/soundboard/) my official HOWTO mascot.  "_Using a key to gouge expletives on another’s vehicle is a sign of trust and friendship_"
