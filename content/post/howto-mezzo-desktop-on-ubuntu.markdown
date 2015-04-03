---
title: "HOWTO: Mezzo desktop on Ubuntu"
slug: "howto-mezzo-desktop-on-ubuntu"
date: "2006-01-31T15:10:00-06:00"
author: "fak3r"
categories:
- linux
tags:
- howto
---

> **UPDATED** (10/27/2006): Old debs are no longer available, newer ones coming soon, I will update the doc once we can get to them. From Mezzo's developer Ryan, "_Currently there are not. I am working to get a new repo online soon. The debs that were there were out of date. The new repo will have up to date packages. The next release 2006-12 will be released at the end of November_."




> **UPDATED** (02/01/2006): installation steps updated to reflect corrections highlighted in [this post](http://www.symphonyos.com/forum/index.php?showtopic=662&view=findpost&p=3814) on the Symphony Forums. Thanks Ryan.


![250px-symph-032.png](http://fak3r.com/wp-content/uploads/2006/10/250px-symph-032.png)
Seeing a new Linux distro out there is kind of like seeing another cloud in the sky, but every now and then something comes along with a fresh idea or approach; enter [Symphony OS](http://www.symphonyos.com/). Described on their website, ”_Symphony OS is a Desktop computer operating system based on Debian GNU/Linux and Knoppix GNU/Linux. Rather than using the KDE or Gnome Desktop environments as most Linux distributions do, the Symphony OS team has created the revolutionary [Mezzo Desktop environment](http://www.symphonyos.com/mezzo.html). Symphony provides what we consider to be the easiest to use Linux experience there is_.” I really like their idea that, ”_Mezzo disposes of standard concepts like “The desktop is a folder_” and referring to it as a “junk drawer”. Whatever distros or OS I’m on (Linux, OS X, Winders) I _always_ dump all of my desktop icons, and have since I figured out how to back in the Gnome 1.4 days! So while the distro is interesting, the real attraction is the new desktop environment, and how they’ve dealt with things differently. Another overview of what Symphony OS was born out of from [this post](http://www.squidoo.com/symphonyos/) reads, ”_Symphony OS is a Linux-based operating system that attempts to correct what are percieved interface errors present in todays modern computing interfaces. It does so through an interface known as the Mezzo Desktop, integrating ideas from [Fitt’s Law](http://ei.cs.vt.edu/~cs5724/g1/), as well as [Jason Spisak’s Laws of Interface Design](http://www.symphonyos.com/laws.html)_.” Needless to say, this is far from just another distro, it addresses UI and useability issues that no other other UI has successfully improved upon; this is exciting. Looking at the screenshots it’s hard to see that too much is changed, but when you actually demo it you’ll get how the “Laws” referred to above are applied, and it’s very nice. Looking through the (now outdated) [Mezzo UI 1.0 Mockups](http://homepage.mac.com/jasonspisak/Mezzo/PhotoAlbum4.html) will give you a bit more into the feel for the look of the desktop. A novel approach is that although the window manager (somehow) uses FVWM, it’s also apparently served up via a local webserver, allowing for a desktop to support web apps, ala “Web 2.0” stuff like gmail, Flickr and the like. Think about how Konfabulator, OS X Widgets allowed for bits and pieces of the web to enter the desktop (RSS, weather, ebay searches) and then you’ll see how Mezzo’s approach can really opens things up in a way no other desktop every has, and I can see developers chomping at the bit on that one. Until now you’ve had to download/install their Symphony OS to play around with Mezzo, but now it’s available to use on Debian based distros, [Ubuntu Linux](http://ubuntulinux.org/) included. Here I present the first HOWTO on installing Mezzo on Ubuntu. While they document this on their site, the docs are incomplete, thus I felt the need to share (and I will share them with them as well). I’ll update this as I learn how to add more to the desktop, it looks like this is going to be a fun alternative to the KDE/Gnome contingent, and I can’t wait for a 1.0.




To install the Mezzo, and the requred Orchestra package, on Ubuntu Linux 5.10 (Breezy Badger) become root:

    
    <code>su - root</code>


Or if you don’t have a regular root account (as is the default in Ubuntu) do:

    
    <code>sudo su -</code>


And then enter your normal users’ password when prompted.

Next add the following to you /etc/apt/sources.list:

    
    <code>deb http://archive.progeny.com/symphonyos/apt/ ./</code>


Now update apt:

    
    <code>apt-get update</code>


And then install Mezzo and Orchestra packages, with a couple of other needed deps:

    
    <code>apt-get install mezzo orchestra libstdc++5 xdialog</code>


(Optional) Install the [MetaTheme](http://www.metatheme.org/), which is “…a project dedicated to unification of appearance between different graphics toolkits (currently GTK2, QT and Java).” According to Symphony OS creator, Ryan, ”_MetaTheme is in no way required. It makes things look pretty but that is about it_” Also, currently the bundled MetaTheme package is broken [[source](http://www.symphonyos.com/forum/index.php?showtopic=662&view=findpost&p=3813)] so you should not install it via apt-get. Instead, either wait for an updated package, or do what I did; download the tarball and follow the manual install steps. MetaTheme needs the dev headers for libgtk2, which I didn’t have installed, so make sure you have those by using apt-get:

    
    <code>apt-get install libgtk2.0-dev</code>


Now get, configure, install and configure Metatheme:

    
    <code>wget http://www.metatheme.org/download/metatheme-0.0.6.tar.bz2
    tar xvjf metatheme-0.0.6.tar.bz2
    cd metatheme-0.0.6
    ./configure</code>


Watch for errors, then make it:

    
    <code>make</code>


Finally install it:

    
    <code>make install</code>


Then back to your normal user (doesn’t sound right to me either, but…) to activate Metatheme in all supported toolkits:

    
    <code>exit
    metatheme-install</code>


Now you have to restart your session, so logout of your Desktop Manager and come back in. Next run the ‘mt-config’ application as your normal user to configure Metatheme and choose your favorite theme:

    
    <code>mt-config</code>






**UPDATE**: thanks to comments from Gabriele Tassoni below, here’s another step that is very important. You have to manually copy the .metatheme directory from your normal user’s home directory to root’s home. Without doing this no GUI apps that need root/sudo privledges (Synaptic for Debian/Ubuntu users is a major one) will run! I’m going to email the MetaTheme devs about this to see if they can fix it for the next release. So, to avoid this pitfall:

    
    <code>sudo cp -R ~/.metatheme /root/.metatheme</code>


Now, back to the Mezzo install, you need to copy the Mezzo and Orchestra settings and base desktop files to the home directory, so again as your regular user:

    
    <code>cp -Rvf /etc/skel/.mezzo ~/
    cp -Rvf /etc/skel/.orchestra ~/
    cp -Rvf /usr/share/fvwm/.fvwm2rc ~/.fvwm</code>


Once you’re back up and in your default desktop, logout, then from your GDM or KDM login manager, login as normal, but choose the Mezzo ‘Session’.

If GDM or KDM does not show Mezzo as an option, you may need to restart the login manager for it to find it (I didn’t need to do this, but Ryan pointed it out):

    
    <code>/etc/init.d/gdm restart</code>


or

    
    <code>/etc/init.d/kdm restart</code>


That’s it, once in click in the corners to get aquinted and start playing around. I’ll post more about this as I learn it, I think it’s pretty cool at this stage.

