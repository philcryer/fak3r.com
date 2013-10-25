---
author: fak3r
comments: true
date: 2012-04-30 09:38:46
layout: post
slug: howto-create-a-linux-livecd
title: 'HOWTO create a Linux LiveCD '
wordpress_id: 3758
categories:
- geek
- howto
- linux
tags:
- bootable cd
- debian
- i386
- linux
- livecd
- wheezy
---

I'm working on a project that calls for a Linux LiveCD, so it can be booted off of a CD-ROM or USB drive, and not require any sort of permanent install to run. You've probably seen this in things like [Ubuntu's LiveCD](https://help.ubuntu.com/community/LiveCD) where you can try the latest version without having to install it, or you can even install it from within the Live environment, but I'm not trying to do that. I just want a Linux environment running and providing DHCP and other network services for the network it finds itself on. While I found some great HOWTOs online, most were [outdated](http://www.pendrivelinux.com/create-your-own-live-linux-cd-or-usb-distribution/), or [just didn't do all that I wanted](http://www.linuxjournal.com/article/7246), so it was time for a new one. Now, since this is going to target a server role I'm going to be kicking it with [Debian](http://www.debian.org/), and they have a set of tools called [Live Tools](http://live.debian.net/devel/live-tools/) to assist in the building of a Debian LiveCD. This method requires working from an existing Debian instance, so get there, and then update your `/etc/apt/sources.list`to include the Live Tools

<!-- more -->

    
    deb http://live.debian.net/ wheezy-snapshots main contrib non-free
    deb-src http://live.debian.net/ wheezy-snapshots main contrib non-free




Notice I'm using Wheezy (testing), so make sure you use the version that's right for you. Next we'll add the developer's keys



    
    wget http://live.debian.net/debian/project/keys/archive-key.asc -O - | apt-key add -




and finally update and install the live helper package



    
    apt-get update
    apt-get install live-build




## Build it




Now create a directory to hold the config files, packages, and the chroot environment that we'll be building the LiveCD in



    
    mkdir my-livecd
    cd my-livecd




We're going to create a simple test LiveCD (no X, just shell) start by building the configuration



    
    lb config




Now you have a config directory with some simple default settings in commented files that you can read and edited by hand. This is where you will be adding your config files, packages (.debs) and the contents of your skel directory...later. This will probably be the most important directory for live-build distro builders and you can get all the lb usage config from the man page



    
    man lb_config




But for now, let's build a stock i386 image with the base packages



    
    lb config --architecture i386 --archive-areas "main contrib non-free"




Now, let's kick off the build



    
    lb build




Depending on your computer's speed, number of packages chosen, etc, after 15-30 minutes you'll have a new file in the my-livecd directory called `binary-hybrid.iso`. This newly built ISO is your live image, mine turned out to be 184 Meg, so copy that to a CD, USB thumb drive or better, just test it out with [Virtualbox](https://www.virtualbox.org/), and see how it boots. Once you see that work you'll probably want to add more default packages for the build to install, and setup/configure certain things; this is where the fun happens.





## Customize with packages




Customize your Linux LiveCD by adding more packages to the configuration you have already setup



    
    lb config –packages “irssi screen obmenu obconf iptraf vim”




Then, since you're editing and rebuilding the configuration, you need to run the following each time



    
    lb clean
    lb build




This will kick off the build again, but this time it'll use the packages that have already been downloaded, so it won't take as long





## Customize with meta-packages




Another way to add packages is to use a short cut to add _meta-packages_, like so



    
    lb config --architecture i386 --archive-areas "main contrib non-free" --packages-lists xfce




You can find more packages lists in `/usr/share/live/build/package-lists/` for desktops (gnome, kde) and servers (minimal, ubuntu-cloud), take a look in there and you'll get a feel for how they're setup.



    
    debian-forensics   gnome         kde         lxde          ubuntu-cloud
    debian-junior           gnome-core    kde-core    minimal       ubuntu-cloud-desktop
    debian-live-devel       gnome-full    kde-extra   rescue        xfce
    debian-live-pxe-server  gnome-junior  kde-full    standard      xfce-junior
    debian-science          gnustep       kde-junior  standard-x11




Of course you can define your own lists here and call them out as you would any other, just follow any of them as a template.





## Customize manually




When you really want to make specific changes, you can make them to the system interactively during the build process in a shell, using interactive shell parameter.



    
    lb clean
    lb config --interactive shell
    lb build






Now a prompt appears that allows you to make the changes you want, it starts with a live prompt so you know you are inside of a chroot environment - this will become obvious if you start looking for files on your system, that aren't in this new chroot

    
    (live)




Once you're done, just log out with `exit` and it will finish the build process on its own. To turn off the interactive shell again for unattended builds run



    
    lb config --interactive disabled





## Customize with hooks




I've saved the best (beast?) for last, customize with hooks. Hooks are just shell scripts that operate as if you were typing them into the command-line. You can see examples of these in



    
    /usr/share/live/build/hooks/




To create your own hook script you can use the following as a simple example. Create the hook script in the `config/chroot_local-hooks/` directory




    
    #!/bin/sh
    echo "HOOK: ssh server"
    # install
    apt-get install --yes --force-yes -y openssh-server
    # disable root login
    echo "I: disabling root login in ssh"
    sed -i "s/PermitRootLogin yes/PermitRootLogin no/" /etc/ssh/sshd_config
    # don't start ssh on boot if you don't need it
    update-rc.d -f ssh remove




Make the file executable and rebuild as normal. It will run that script during the build phase



    
    lb clean
    lb config
    lb build





## Persistence




Sometimes you'll hear about these desktop LiveCDs that allow you to save files and settings, this is just done with a thumb drive that the LiveCD auto saves to. To do this, just create a new partition and change the label of the new partition to `home-rw`. Put it in your system, boot-up, and enter `persistent` as a boot option to use it. Now any changes in the $HOME will survive and be used again booting with persistent again.





## Conclusion




Building a Linux LiveCD isn't that hard, especially when you have these specialized tools that take over so much of the action. In the end you have a bootable desktop, or network application, that you can use, and not worry about saving anything to in case of privacy/security, or just not having access to your own machine. Once I have my project off the ground I'll announce it here and put it up on Github for all to see, there's tons you can do with a customized Linux LiveCD system. If you need more help ask away, and also, Debian provides an [online manual](http://live-manual.debian.net/manual-3.x/manual/html/live-manual.en.html) that got me this far.



