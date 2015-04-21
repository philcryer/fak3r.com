---
title: "HOWTO enable automatic security updates in Debian"
slug: "howto-enable-automatic-security-updates-in-debian"
date: "2012-06-21T21:50:24-06:00"
author: "fak3r"
categories:
- geek
- howto
- linux
tags:
- autonomous
- autonomy
- debian linux
- linux
- ubuntu linux
---

In installs of the latest [Ubuntu Linux](http://ubuntu.com) you've given the option at the end to turn on automatic updates for security updates, which a great option for a server. I've always been a big purveyor of autonomous computing, after all, they know how to do their job, so give them enough rope to hang^K^K^K do it themselves. The old way of running `cron-apt` followed by `apt-get upgrade` is a big hammer for a small job and _will_ break services, it's a guarantee. So I knew there had to be a better way, and I'm sure Ubuntu had already formalized it, so it wasn't that hard to figure out. It starts with the install of a package named `unattended-upgrades`. Ok, so this was likely available for some time, well as they say, it's new to me!
<!-- more -->

    
    apt-get install unattended-upgrades


Next up, we need to setup preferences for the apt package, do this creating a new file `/etc/apt/apt.conf.d/02periodic` and filling it in with the following:

    
    APT::Periodic::Enable "1";
    APT::Periodic::Update-Package-Lists "1";
    APT::Periodic::Download-Upgradeable-Packages "1";
    APT::Periodic::AutocleanInterval "5";
    APT::Periodic::Unattended-Upgrade "1";


Seems pretty self-explanatory, with some sane options to start with, the 1 tells it how many times to run (once a day) while autoclean will occur every 5. Note that any activity from the program will be logged to `/var/log/unattended-upgrades` which is helpful, but next we'll see how to enable email alerts as well. Now we'll setup the preferences for the unattended-upgrades program. The file that controls this is `/etc/apt/apt.conf.d/50unattended-upgrades`, and it will look something like this:

    
    // Automatically upgrade packages from these (origin, archive) pairs
    Unattended-Upgrade::Allowed-Origins {
            "${distro_id} stable";
            "${distro_id} ${distro_codename}-security";
    //      "${distro_id} ${distro_codename}-updates";
    //      "${distro_id} ${distro_codename}-proposed-updates";
    };
    
    // List of packages to not update
    Unattended-Upgrade::Package-Blacklist {
    //      "vim";
    //      "libc6";
    //      "libc6-dev";
    //      "libc6-i686";
    };
    
    // Send email to this address for problems or packages upgrades
    // If empty or unset then no email is sent, make sure that you
    // have a working mail setup on your system. The package 'mailx'
    // must be installed or anything that provides /usr/bin/mail.
    //Unattended-Upgrade::Mail "root@localhost";
    
    // Do automatic removal of new unused dependencies after the upgrade
    // (equivalent to apt-get autoremove)
    //Unattended-Upgrade::Remove-Unused-Dependencies "false";
    
    // Automatically reboot *WITHOUT CONFIRMATION* if a
    // the file /var/run/reboot-required is found after the upgrade
    //Unattended-Upgrade::Automatic-Reboot "false";
    
    // Use apt bandwidth limit feature, this example limits the download
    // speed to 70kb/sec
    //Acquire::http::Dl-Limit "70";


So there's some cool options including what to allow to be upgraded, blacklisted apps, email notifications and even an auto-reboot option. So if there's a security update in the kernel, it will install the new kernel and reboot the system automatically - how cool is that? This is the autonomy I was looking for! Now having said that, I think I'll throw this in just because, Manchester, England band [Buzzcocks](https://en.wikipedia.org/wiki/Buzzcocks) doing `Autonomy` from 1978.

<div align="center">
<iframe width=”640” height=”360” src=”https://www.youtube-nocookie.com/embed/J7HJXCYPCuU” frameborder=”0” allowfullscreen></iframe>
</div>
