---
author: phil
comments: true
date: 2009-09-14 23:21:03
layout: post
slug: howto-build-your-own-open-source-dropbox-clone
title: HOWTO build your own open source Dropbox clone
wordpress_id: 1727
categories:
- geek
- howto
- linux
tags:
- backup
- diy
- dropbox
- howto
- linux
- mirror
- open source
- server
- sync
---

![I KAN HAZ OPEN-SRC DROPBX?](http://fak3r.com/wp-content/uploads/2009/09/opensource-dropbox.png)****

**UPDATE #4 **It's 2012, and this project is still alive, although I haven't worked on lipsync as much as I should.  I want to, and have new ideas to implement and try out in the next few months. The two way sharing is a bit hacky, and I don't like it, the installer creates a [cronjob](https://github.com/philcryer/lipsync/blob/master/install.sh#L155):  that checks for server changes to sync back every minute - and it tries to avoid conflicts by [not running if a sync the other way is happening](https://github.com/philcryer/lipsync/blob/master/bin/lipsync#L9). Yes, if you're using 2 computers at once it could get confused, but so far, it's pretty good - but something I want to improve. I'm also very interested in [ownCloud](http://owncloud.org/)  and using [remote storage](http://www.w3.org/community/unhosted/wiki/RemoteStorage) auth protocol like [Unhosted](http://unhosted.org/) proposes - these are two things I'd love to integrate into lipsync over the next few months. I really think having something that is all owned by the user, and in full control of the user, is still the ultimate way. Watch the [lipsync.info](http://lipsync.info/) site for more details, thanks.

**UPDATE #3:** Ok, a long, overdue update on this project. I've worked on the next version of this ideal that I encourage everyone to checkout and try for themselves. You can get it on Github, and** the project's name is [lipsync](https://github.com/philcryer/lipsync)**. My goal is to make something that is trivial for anyone to setup and use, providing them a ‘Dropbox-like’ experience. This process will also help [send](https://www.yousendit.com/) large files and overcome file size limitations. As before I've focused on the backend, server side, part of the game to get that working, but would be happy to work with anyone that wanted to work on a GUI, or integrate this with existing projects, such as [Sparkleshare](http://sparkleshare.org/), which seems to have a great GUI, but a backend that relies on things like Github for storage. So give it a look and remember, the more feedback the better; and as always don't worry about offending me! Thanks.

**UPDATE #2:** _There was a big influx of new hits/posts on this article last week thanks to [Lifehacker Australia linking to it](http://www.lifehacker.com.au/2010/10/how-to-make-your-own-dropbox-like-sync-and-backup-service/), plus they even came up with a [pretty sweet logo](http://cache.gawker.com/assets/images/lifehacker/2010/10/opensource-dropbox.jpg). It's v__ery cool that so many are (still) interested in this project - and that's what it has become; a project. I'll be releasing code to setup a complete command-line Dropbox like implementation on Linux in about a week. Code will be hosted on github.com and I'm hoping it will spur others to work on cross platform front-ends to talk to it. So far the technology is there, I'm just using what others have built, it's just a matter of hooking it all up! After all, why reinvent the wheel? (not that I could ;)) __Thanks again for all the comments and support!_

_**UPDATE: **__Thanks to everyone who has contributed to this, and the [Reddit thread](http://www.reddit.com/r/linux/comments/9ol1j/howto_create_your_own_dropbox_clone/), as it has provided some great ideas building off of my concept.  I'm starting to rethink about how we could have version control on top of things, and I'll update things when I have more to share.  Also, does anyone have [iFolder](http://www.kablink.org/ifolder) (thanks for the proper link salubrium) working?  It looks like you need SUSE Linux, which I don't have access to, plus I know most Novell projects need a *ton* of Mono dependencies installed to have any of their stuff working, at least on the server side; but it sounds like they have Mac, Linux and Windows clients, which is encouraging.  While for my needs something a bit more 'close to the bone' (as below) might be better for the server side, having it be inter-operable with something like iFolder could provide a lot more functionality for others._

First off, if you haven't tried [Dropbox](https://www.getdropbox.com/referrals/NTY0OTQ0MDk), you should check it out; sync all of your computers via the Dropbox servers, [their basic free service gives you 2Gigs of space](https://www.getdropbox.com/referrals/NTY0OTQ0MDk) and works cross-platform (Windows, Mac, Linux).  I use it daily at home and work, and just having a live backup of my main data for my work workstation, my home netbook, and any other computer I need to login to is a huge win.  Plus, I have various 'shared' folders that distribute certain data to certain users that I've granted access to, this means work details can be updated and automatically distributed to the folks I want to review/use the data.  I recommend everyone try it out, and see how useful it is, it's turned into a game changer for me.  So a few months ago they made headlines on supporting Linux as they released the client as open source. While this got hopes up for many, it was only the client that was open source, the server is still proprietary.  While slightly disappointing, this is fine, they're a company trying to make money.  I don't fault them for this, it's just that a free, portable service like that would be a killer app.<!-- more -->

Meanwhile at work I'm working on a solution to sync large data clusters online and the project manager described it as the need for 'Dropbox on steroids'.  Before I had thought it was more complicated, but after thinking about it, I realized he was right.  Look, Dropbox is a great idea, but it obviously is just a melding of rsync, with something watching for file changes to initiate the sync, along with an easy to use front end.  From there I just started looking at ways this could work, and there are more than a few; here's how I made it work.

Linux now includes [inotify](http://en.wikipedia.org/wiki/Inotify), which is a kernel subsystem that provides file system event notification.  From there all it took was to find an application that listens to inotify and then kicks off a command when it hears of a change.  I tried a few different applications like inocron, inosync and iwatch, before going with [lsyncd](http://code.google.com/p/lsyncd/).   While all of them could work, lsyncd seemed to be the most mature, simple to configure and fast.  Lsyncd uses inotify to watch a specified directory for any new, edited or removed files or directories, and then calls rsync to take care of business.  So let's get started in making our own open source Dropbox clone with [Debian GNU/Linux](http://debian.org) (lenny)

<!-- more -->


## Ladies and gentlemen, start your <del>engines</del> servers!


First, you need 2 severs; one being the server and the other the client. (you could do this on one host if you wanted to see how it works for a proof of concept)


## Install OpenSSH server


First you'll need to install OpenSSH Server on the remote system:
apt-get install openssh-server


## **Configure SSH for Passwordless Logins**


You'll need to configure passwordless logins between the two hosts you want to use, this is how rsync will pass the files back and forth.  I've previously written a [HOWTO on this topic](http://fak3r.com/2006/08/10/howto-passwordless-ssh-logins/), so we'll crib from there.

First, generate a key:

    
    ssh-keygen -t rsa


**UPDATE**: actually, it's easier to do it this way

    
    ssh-keygen -N '' -f ~/.ssh/id_dsa


(Enter)

You shouldn’t have a key stored there yet, but if you do it will prompt you now; make sure you overwrite it.

    
    Enter passphrase (empty for no passphrase):


(Enter)

    
    Enter same passphrase again:


(Enter)

We’re not using passphrases so logins can be automated, this should only be done for scripts or applications that need this functionality, it’s not for logging into servers lazily, and **it should not be done as root**!

Now, replace REMOTE_SERVER with the hostname or IP that you’re going to call when you SSH to it, and copy the key over to the server:

    
    cat ~/.ssh/id_rsa.pub | ssh REMOTE_SERVER 'cat - >> ~/.ssh/authorized_keys2'


**UPDATE: **now you can use ssh-copy-id for this instead (hat tip [briealeida](/briealeida/))

    
    ssh-copy-id REMOTE_SERVER


Set the permissions to a sane level:

    
    ssh REMOTE_SERVER 'chmod 700 .ssh'


Lastly, give it a go to see if it worked:

    
    ssh REMOTE_SERVER


You should be dropped to a prompt on the remote server. If not you may need to redo your .ssh directory, so on both servers:

    
    `mv ~/.ssh ~/.ssh-old`


and goto 10


## Install rsync and lsyncd


Next up is to install rsync and lsyncd.  First, rsync is simple, and could already be installed (you don't need to run it as a server, just the client), make sure you have it with:

    
    apt-get install rsync


Next is lsyncd.  There is no official Debian package yet, but it's simple to build from source and install.  First off, if you don't have build essentials you'll need them, as well as libxml2-dev to build the lsyncd source.  Installing those is as simple as:

    
    apt-get install libxml2-dev build-essential


Now we'll get the lsyncd code (you can check for a newer version at [http://lsyncd.googlecode.com](http://lsyncd.googlecode.com)) and build that:

    
    wget http://lsyncd.googlecode.com/files/lsyncd-1.26.tar.gz
    tar -zxf lsyncd-1.26.tar.gz
    cd lsyncd-1.26
    ./configure
    make; make install


This install does not install the configuration file, so we'll do that manually now:

    
    cp lsyncd.conf.xml /etc/




## Configure lsyncd


Next up, we'll edit the configuration file now located in /etc  The file is a simple, well documented XML file, and mine ended up like so - just be sure to change the source and target hosts and paths to work with your systems:

    
    <span style="color: #000000;"><lsyncd version="1.25">  </span>



    
    <span style="color: #000000;"> <settings> </span>



    
    <span style="color: #000000;"> <logfile filename="/var/log/lsyncd"/> </span>



    
    <span style="color: #000000;"> <!--Specify the rsync (or other) binary to call--> </span>



    
    <span style="color: #000000;"> <binary filename="/usr/bin/rsync"/> </span>



    
    <span style="color: #000000;"> <!--uncomment to create a file containing pid of the daemon--> </span>



    
    <span style="color: #000000;"> <!--pidfile filename="/tmp/pid"/--> </span>



    
    <span style="color: #000000;"> <!--this specifies the arguments handled to the rsync (or other) binary. </span>



    
    <span style="color: #000000;"> option is the default literal. only '%r' will be replaced with r when recursive</span>



    
    <span style="color: #000000;"> operation is wanted, d when not. exclude file will be replaced with -exclude-from FILE </span>



    
    <span style="color: #000000;"> source will be the source path to sync from destination will be the</span>



    
    <span style="color: #000000;"> destination path to sync to --> </span>



    
    <span style="color: #000000;"> <callopts> </span>



    
    <span style="color: #000000;"> <option text="-lt%r"/> </span>



    
    <span style="color: #000000;"> <option text="--delete"/> </span>



    
    <span style="color: #000000;"> <exclude -file/> </span>



    
    <span style="color: #000000;"> <source /> </span>



    
    <span style="color: #000000;"> <destination /> </span>



    
    <span style="color: #000000;"> </callopts>  </span>



    
    <span style="color: #000000;"> </settings> </span>



    
    <span style="color: #000000;"> <directory> </span>



    
    <span style="color: #000000;"> <source path="/var/www/sync_test"/> </span>



    
    <span style="color: #000000;"> <target path="desthost::module/"/> </span>



    
    <span style="color: #000000;"> <!-- or it can also be an absolute path for localhost </span>



    
    <span style="color: #000000;"> <target path="/absolute/path/to/target"> --> </span>



    
    <span style="color: #000000;"> </directory> </span>



    
    <span style="color: #000000;"></lsyncd> </span>




## Launch lsyncd in debug for testing


We're ready to give it a go, may as well run it in debug for fun and to learn how lsyncd does what it does:

    
    lsyncd --conf /etc/lsyncd.conf.xml --debug


Watch for errors, if none are found, continue.


## Add files and watch them sync


Now we just need to copy some files into this directory on the source box:

    
    /var/www/sync_test


And again, watch for any errors on the screen, if these come back as a failed connection it'll be an SSH/key issue, common, and not too difficult to solve. From here add some directories and watch how they're queued up, and then take a look at them on the remote box: from this point out it "just works". Now give it more to do by adding files and directories, and then the logging for errors while they sync. As it stands the system uses the source system as the preferred environment, so any files that change, or are added or removed, will be processed on the remote system. This is analogous to how Dropbox works, you can use multiple sources (your laptop, your desktop, etc) and their server serves as the remote system, keeping all the clients in line.


## Conclusion


You should now have a basic, working Dropbox style setup for your own personal use. I had this running and used it to sync my netbook back to my home server, and then have my work desktop sync to my home server, so both the netbook and the desktop would stay in sync without me doing anything besides putting files in the specfied folder. For my week long test I ran a directory alongside my Dropbox directory just to see how they both acted, and I didn't have any failures along the way.

Now we have is a simple Dropbox style app that is lightweight, with a functional back-end running rsync, which is a known stable app that will scale, and while it doesn't provide the front-end and web view that Dropbox does, that could be an easy part for a UX developer to tackle. The cool thing is, we have a solution that works, and other options like the apps I described in the beginning, can be dropped in and replace the functionality lsyncd provides in case they can do something better. For now, I'm playing around with it to learn the ins and outs of the system to see how it will behave long term under a much larger store (50Gig to start) to keep in check. I will also work on better integrating this solution it into a working system, and update this tread with init scripts, reports, or maybe even a web view beyond just an index view from Apache or nginx. Ideally we could have a web front end that would intelligently report if a file is complete on the server, and if the file is completely mirrored on another server or client. P2P or Bitorrent would also be really cool to consider with this, and I'm sure there will be more applications for a setup like this once we've it around as a resource for a time. Can you think of more applications for this? Did you get it to work? Can you think of a better way to do this?
