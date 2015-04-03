---
title: "HOWTO install Samba on Solaris"
slug: "howto-install-samba-on-solaris"
date: "2012-02-15T21:35:26-06:00"
author: "fak3r"
categories:
- geek
- howto
tags:
- pkgadd
- samba
- solaris
- sun
---

[![Sun Fire X4500 (code-named Thumper)](http://fak3r.com/wp-content/blogs.dir/12/files/k3_j4500-array_1-300x239.jpg)](http://fak3r.com/2012/02/15/howto-install-samba-on-solaris/k3_j4500-array_1/)

At work we've had an old(ish) [Sun Fire X4500](http://en.wikipedia.org/wiki/Sun_Fire_X4500) (aka Thumper), that has been under utilized. With 48 harddisks, it has about 40 Terrabytes of storage, not too shabby for a 4 rack unit box. Of course digging in and actually doing stuff in Solaris is not as much fun as Linux, but we needed to get Samba running on this montster to give us some breathing room for our storage. From running Sun years ago I remember pkg-get, so I gave that a go, but by default, at least on this box, pkg-get used [Blastwave](http://blastwave.network.com/), which seems to has gone away, so I spec'd pkg-get manually and told it to use [Sunfreeware](http://sunfreeware.com) instead. That got the game going, but it was a long road to (evenutal) success. But hey, long road, this is why I do what I do, let's get it started!

<!-- more -->

First we'll update the catalog of available software from the provider we spec

    
    pkg-get -s ftp://ftp.sunfreeware.com/pub/freeware/ -U


I got an error saying, "No such file `descriptions'", but it saved a file called catalog that lists all the available packages. I grep'd it out to see what fits:

    
    # cat catalog | grep samba
     samba 3.0.10 SMCsamba samba-3.0.10-sol10-intel-local.gz
     samba 3.0.23d SMCsamba samba-3.0.23d-sol10-x86-local.gz
     samba 3.0.24 SMCsamba samba-3.0.24-sol10-x86-local.gz
     samba 3.0.25 SMCsamba samba-3.0.25-sol10-x86-local.gz
     samba 3.0.25a SMCsamba samba-3.0.25a-sol10-x86-local.gz
     samba 3.0.28a SMCsamba samba-3.0.28a-sol10-x86-local.gz
     samba 3.0.9 SMCsamba samba-3.0.9-sol10-intel-local.gz
     samba 3.2.0 SMCsamba samba-3.2.0-sol10-x86-local.gz
     samba 3.2.14 SMCsamba samba-3.2.14-sol10-x86-local.gz
     samba 3.2.4 SMCsamba samba-3.2.4-sol10-x86-local.gz
     samba 3.4.0 SMCsamba samba-3.4.0-sol10-x86-local.gz
     samba 3.4.2 SMCsamba samba-3.4.2-sol10-x86-local.gz
     samba 3.5.3 SMCsamba samba-3.5.3-sol10-x86-local.gz
     samba 3.5.6 SMCsamba samba-3.5.6-sol10-x86-local.gz


Then I installed the latest version, 3.5.6:

    
    pkg-get -s ftp://ftp.sunfreeware.com/pub/freeware/ -i samba-3.5.6


But that failed to checksum the file, and said

    
    NOTE: No checksum available for package
    Analysing special files...
    Hmmm. Retrying with different archive offset...cpio: Impossible header type.
    1 errors
    ERROR: cpio still failed
    ERROR: could not verify downloaded file correctly


Hmmm indeed, after poking around a bit I decided just to download the file and install it manually:

    
    pkg-get -s ftp://ftp.sunfreeware.com/pub/freeware/ -i samba-3.5.6 -D
    cd /var/pkg-get/downloads/
    gunzip samba-3.5.6-sol10-x86-local.gz
    pkgadd -d samba-3.5.6-sol10-x86-local


It asked if I wanted to install 'all' packages

    
    The following packages are available:
    1 SMCsamba samba
    (x86) 3.5.6
    
    Select package(s) you wish to process (or 'all' to process
    all packages). (default: all) [?,??,q]:


I typed 'all' and it installed. The last step was to get it running

    
     cd /usr/local/samba/
     ./bin/smbcontrol


But it complained about an LDAP library not being present. So I downloaded and installed that

    
     pkg-get -s ftp://ftp.sunfreeware.com/pub/freeware -i openldap-2.4.9
     cd /var/pkg-get/downloads/
     gunzip openldap-2.4.9-sol10-x86-local.gz
     pkgadd -d openldap-2.4.9-sol10-x86-local


Next up, some libwbclient.so error

    
    ld.so.1: smbcontrol: fatal: libwbclient.so.0: open failed: No such file or directory
     Killed


After banging around on this, I think I had the proper bits installed, but still got an error when I tried to launch smbd

    
    ld.so.1: smbd: fatal: libc.so.1: version `SUNW_1.22.6' not found (required by file /opt/csw/sbin/amd64/smbd)
     ld.so.1: smbd: fatal: libc.so.1: open failed: No such file or directory


I fired up IRC and hit up #openindian on irc.freenode. They were pretty helpful, told me that I probably had an older version of Solaris, so I'd need older packages, or would need to build Samba from source. Once they saw the errors they directed me to #opencw who then pointed towards <a href="http://mirror.opencsw.org/experimental/pkgutil/">pkgutil</a>. After installing it, I was told to read the conf file, where I could specify a branch to use, and sure enough, there was a 'legacy' version that sounded promising. Knowing all of this I uninstalled the Samba that pkg-get gave me, and then edited /etc/opt/csw/pkgutil.conf and set this option so it would use 'legacy' packages

    
    mirror=http://mirror.opencsw.org/opencsw/legacy/


After this there was I had to update it to use the new configuration, and then an install of Samba, which was totally painless

    
    pkgutil -U
     pkgutil -i samba


Now when I issued smbd I don't get the above error, and smbstatus gives me

    
    params.c:OpenConfFile() - Unable to open configuration file "/opt/csw/etc/samba/smb.conf":
     No such file or directory
     Can't load /opt/csw/etc/samba/smb.conf - run testparm to debug


Ah, it feels like we're almost there! Next I edited /opt/csw/etc/samba/smb.conf and used a mostly default Solaris Samba config found online

    
    [global]
     workgroup = FS
     server string = Samba Server
     security = SHARE
     log file = /var/log/samba.log



    
    [vaultstores]
     comment = storage
     path = /0/storage
     force user = evault
     force group = other
     read only = No
     guest ok = Yes


And then tested this configuration with

    
    testparm


This completed without error for me, so the last thing to do was to start it up.

    
    smbd


This returned with no info, so I looked at the samba.log, it looked good, so I ran a status

    
    smbstatus



    
    Samba version 3.0.22
     PID Username Group Machine
     -------------------------------------------------------------------



    
    Service pid machine Connected at
     -------------------------------------------------------



    
    No locked files


And that's it, the server is up, now we just have to have clients connect to it. Wow, that was fun, but I hope I don't have to do it again.

TL;DR install pkgutil, install samba from that, get help on IRC and online.
