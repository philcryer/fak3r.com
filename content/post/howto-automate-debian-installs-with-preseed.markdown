---
title: "HOWTO automate Debian installs with preseed"
slug: "howto-automate-debian-installs-with-preseed"
date: "2011-08-18T14:41:14-06:00"
author: "fak3r"
categories:
- geek
- howto
- linux
tags:
- automate install
- commandline
- debian
- installation
- kickstart
- linux
- os install
- preseed
- preseed.cfg
---

[![](http://fak3r.com/wp-content/blogs.dir/12/files/0904dp_01_z+automatic_transmission+side_angle.jpg)](http://fak3r.com/2011/08/18/howto-automate-debian-installs-with-preseed/0904dp_01_zautomatic_transmissionside_angle/)I've installed Linux, probably 100s of times, and while going through all the questions and answers used to be fun, once you have everything decided it's mainly a case of tab, space, enter, tab, tab, enter, space, space, tab, enter. I remember reading about [kickstart](https://docs.redhat.com/docs/en-US/Red_Hat_Enterprise_Linux/6/html/Installation_Guide/ch-kickstart2.html), which was [Red Hat](https://www.redhat.com/)'s way of automating the install process, but [Debian GNU/Linux](http://debian.org) (and by extension [Ubuntu Linux](http://www.ubuntu.com/)) support  [preseed](http://wiki.debian.org/DebianInstaller/Preseed). From Debian's wiki, "_Preseeding provides a way to set answers to questions asked during the installation process, without having to manually enter the answers while the installation is running. This makes it possible to fully automate most types of installation and even offers some features not available during normal installations._" So preseeding automates the install of the OS, the questions that you'd normally need to answer interactively are predetermined, and defined by a supplied configuration file, and sometimes boot parameters. So while Ubuntu is known for it's user-friendly OS installer, [Ubiquity](http://en.wikipedia.org/wiki/Ubiquity_(software)), preseeding the [Debian-Installer](http://en.wikipedia.org/wiki/Debian-Installer)  (also known as "d-i") is the recommended method for automating Ubuntu installations and for building custom install CDs. With this in mind I set out to build a preseed config file that would automate installs of virtual KVM machines we were provisioning at a gig, but looking at how I do such bare-bones base installs, this would work for most of my normal Debian installs at home too.<!-- more -->

Here is what I came up with to install Debian Squeeze to a system with the first harddrive on the SATA connector (/dev/sda), if you cut and paste this it won't be too hard to modify it for your needs:

    
    # Debian squeeze server preseed file - to automate this, at boot:
    # install auto=true priority=critical preseed/url=http://DOMAIN.COM/path/preseed.cfg
    #
    ### Localization
    d-i debian-installer/language string en
    d-i debian-installer/country string US
    d-i debian-installer/locale string en_US
    # Keyboard selection.
    d-i console-keymaps-at/keymap select us
    d-i keyboard-configuration/xkb-keymap select us
    ### Network configuration
    # Any hostname and domain names assigned from dhcp take precedence over
    # values set here. However, setting the values still prevents the questions
    # from being shown, even if values come from dhcp.
    d-i netcfg/get_hostname string unassigned-hostname
    d-i netcfg/get_domain string unassigned-domain
    # If non-free firmware is needed for the network or other hardware, you can
    # configure the installer to always try to load it, without prompting. Or
    # change to false to disable asking.
    d-i hw-detect/load_firmware boolean true
    ### Mirror settings
    d-i mirror/country string manual
    d-i mirror/http/hostname string ftp.debian.org
    d-i mirror/http/directory string /debian
    d-i mirror/http/proxy string
    # Suite to install.
    d-i mirror/suite string squeeze
    ### Account setup
    d-i passwd/root-login boolean true
    d-i passwd/make-user boolean false
    ### create a password with `printf "r00tme" | mkpasswd -s -m md5`
    d-i passwd/root-password-crypted password $1$ZgNbzcXq$hUR0CnHVtYAvNNNnA2.br1
    ### Clock and time zone setup
    d-i clock-setup/utc boolean true
    #d-i time/zone string US/Central
    d-i time/zone string US/Eastern
    d-i clock-setup/ntp boolean true
    d-i clock-setup/ntp-server string 0.debian.pool.ntp.org
    ### Partitioning
    d-i partman-auto/disk string /dev/sda
    d-i partman-auto/method string regular
    d-i partman-auto/purge_lvm_from_device boolean true
    d-i partman-lvm/device_remove_lvm boolean true
    d-i partman-md/device_remove_md boolean true
    d-i partman-lvm/confirm boolean true
    d-i partman-auto/choose_recipe select atomic
    d-i partman-auto/expert_recipe string                         \
          boot-root ::                                            \
                  40 50 100 ext4                                  \
                          $primary{ } $bootable{ }                \
                          method{ format } format{ }              \
                          use_filesystem{ } filesystem{ ext4 }    \
                          mountpoint{ /boot }                     \
                  .                                               \
                  500 10000 1000000000 ext4                       \
                          method{ format } format{ }              \
                          use_filesystem{ } filesystem{ ext4 }    \
                          mountpoint{ / }                         \
                  .                                               \
                  64 512 300% linux-swap                          \
                          method{ swap } format{ }                \
                  .
    # The full recipe format is documented in the file partman-auto-recipe.txt
    # included in the 'debian-installer' package or available from D-I source
    # repository. This also documents how to specify settings such as file
    # system labels, volume group names and which physical devices to include
    # in a volume group.
    # This makes partman automatically partition without confirmation, provided
    # that you told it what to do using one of the methods above.
    d-i partman-partitioning/confirm_write_new_label boolean true
    d-i partman/choose_partition select finish
    d-i partman/confirm boolean true
    d-i partman/confirm_nooverwrite boolean true
    ### Base system installation
    # Configure APT to not install recommended packages by default. Use of this
    # option can result in an incomplete system and should only be used by very
    # experienced users.
    d-i base-installer/install-recommends boolean false
    # Select the initramfs generator used to generate the initrd for 2.6 kernels.
    d-i base-installer/kernel/linux/initramfs-generators string initramfs-tools
    ### Apt setup
    # You can choose to install non-free and contrib software.
    d-i apt-setup/non-free boolean true
    d-i apt-setup/contrib boolean true
    d-i apt-setup/services-select multiselect security
    d-i apt-setup/security_host string security.debian.org
    ### Package selection
    tasksel tasksel/first multiselect none
    # Individual additional packages to install
    d-i pkgsel/include string openssh-server less htop vim-nox lsb-release zip unzip
    d-i pkgsel/upgrade select full-upgrade
    popularity-contest popularity-contest/participate boolean false
    d-i grub-installer/only_debian boolean true
    ### Finishing up the installation
    d-i finish-install/reboot_in_progress note<span style="font-family: Georgia, 'Times New Roman', 'Bitstream Charter', Times, serif; font-size: 13px; line-height: 19px; white-space: normal;" class="Apple-style-span"> </span>


Take special notice of the partition section, as this is where most of the confusion comes from, I have Reference links at the bottom of this post where you can find more examples. Also don't get too excited, before you go to the trouble to try and decrypt the hash, the temporary root password is 'changeme' :)

Save this file, and then make it available on any webserver you can hit from new machines you want to install (as long as the installer can get DHCP it will jump out, grab the file, and then continue the install with the details in the preseed file). Once this is done, boot the system you want to install to from normal Squeeze install CD, then when you get to the install menu prompt, hit ESC, which will give you boot: prompt. At the prompt type:

    
    install auto=true priority=critical preseed/url=http://DOMAIN.COM/path/preseed.cfg


These commands will ensure that the install will take place automatically, not prompting you for any details, and instead, using the details from the preseed config file you've pointed to. In about 5 minutes the install will be done, and it will boot into the new system when it's complete. Once you get to the login, login as 'root' with the password 'changeme' (change this once you login by typing passwd) - welcome to your new Linux install, you are ...

[![Deon](http://fak3r.com/wp-content/blogs.dir/12/files/done-graphic.png)](http://fak3r.com/2011/08/18/howto-automate-debian-installs-with-preseed/done-graphic/)

Now, for the extra credit... we wanted to have things kick off after the full install was complete, like to bootstrap [chef](http://www.opscode.com/chef/), and really, this is pretty easy to do too. First, at the bottom of your preseed file, have something like this:

    
    # post install script
    d-i preseed/late_command string \
        cd /target; \
        wget http://DOMAIN.COM/path/post-install.sh; \
        chmod +x ./post-install.sh; \
        chroot ./ ./post-install.sh; \
        rm -f ./post-install.sh


And make sure you have that 'post-install.sh' file in place. The file is a simple BASH script that the installer will initiate in a chroot environment after the first boot. Looking at my example it's pretty basic, but the point is, you could do anything at this point, from installing extra packages :

    
    #!/bin/bash
    
    echo "Post install started on `date`" > /root/manifest
    
    echo "* Updating Apt sources..." >> /root/manifest
    wget "$SCRIPTURL/sources.list"
    cp sources.list /etc/apt/sources.list
    
    echo "* Updating system..." >> /root/manifest
    apt-get update
    apt-get -y upgrade
    apt-get -y install monit screen



    
    echo "Post install completed on `date`" >> /root/manifest



    
    exit 0


So while it currently just does some basic updates and installs monit and screen, it could do anything you want it to do after install; lock things down, setup services, whatever. Ok, end of this, did you learn something? Is there a better way to do this? (I know about [fai](http://fai-project.org/), but it seemed like a much bigger project than I needed)



References used during research on this article:



	
  * http://wiki.debian.org/DebianInstaller/Preseed

	
  * http://www.tylerlesmann.com/2008/jul/06/fun-preseed/

	
  * http://lackof.org/taggart/hacking/d-i_preseed/

	
  * http://www.instalinux.com/blog/2011/07/the-anatomy-of-a-preseed-file/

	
  * http://blogs.cae.tntech.edu/mwr/2007/04/17/unattended-debian-installations-or-how-i-learned-to-stop-worrying-and-love-the-preseedcfg/

	
  * https://help.ubuntu.com/10.04/installation-guide/i386/preseed-contents.html

	
  * http://dev.blankonlinux.or.id/browser/nanggar/debian-installer/doc/devel/partman-auto-recipe.txt?rev=nanggar%2Cdebian-installer%2C1


