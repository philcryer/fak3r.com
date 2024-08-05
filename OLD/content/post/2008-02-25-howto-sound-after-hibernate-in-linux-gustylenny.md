---
title: "Sound after hibernate in Linux (Gusty/Lenny)"
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

 that creates a new task for Linux to do before it shuts down and before it starts up.: "_Create the file /etc/pm/sleep.d/49sound..._

    
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
