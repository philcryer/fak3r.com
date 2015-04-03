---
author: fak3r
comments: true
date: 2011-10-25 12:03:03
layout: post
slug: howto-run-dd-wrt-on-a-netgear-wndr3700
title: HOWTO run DD-WRT on a Netgear WNDR3700
wordpress_id: 3380
categories:
- geek
- howto
tags:
- dd-wrt
- firmware
- flash
- linksys
- linksys wrt54gl
- netgear
- netgear wndr3700
- router
- tomato
- wifi
- wndr3700
- wrt54gl
---

<img src="/assets/2011/images.jpeg" border="0" align="right">At home I've had my trusty [Linksys WRT54GL](http://en.wikipedia.org/wiki/Linksys_WRT54G_series#WRT54GL), the Linux based router that ran the [Tomato firmware](http://www.polarcloud.com/tomato) so well, for years; it's an awesome router and the only time it went down was when I was upgrading it. The only reason to look for a new one is that the wifi is G speed, and the network is only 10/100. Eventually newer, sleeker, and far faster routers, tempted me too much. After looking around I found the best bang for the buck, and then I found an online factory reconditioned version and the decision was made. I'm now running the [Netgear WNDR3700](http://www.netgear.com/home/products/wirelessrouters/high-performance/WNDR3700.aspx) (v1) N600 Wireless Dual Band Gigabit Router, and after playing around with the stock firmware, I installed [DD-WRT](http://www.dd-wrt.com/) on it, and couldn't be happier with it. It looks and feels a bit more hardcore than Tomato, and unlocks all sorts of features of the hardware that the factory firmware blocked access to. But first let's look at the router, it's a shiny, thin, tall (if setup with the included stand) router with brighter than average lights that the rim of the router help reflect out, more blinky lights is always a plus in my book. It's a dual-band Wireless-N router, it has simultaneous networks running on 2.4 GHz and 5.0 GHz which support supports A/B/G/N speeds, it has a 680 MHz MIPS 32-bit processor (for comparison the WRT54GL had a 200 Mhz proc), 64 Meg RAM (again, the WRT54GL had 16 Meg RAM), 5 Gigabit ethernet ports, a USB 2.0 port for external networked storage (or networked printer if you're running DD-WRT), dynamic DNS, firewall, and expanded QoS settings to handle things like VPN and VoIP. So that's about it, this is still one of the fastest routers out there, and big step up from where I was previously. I knew I wouldn't need to reference much from it, but I pulled up the [FAQ](http://support.netgear.com/app/answers/detail/a_id/11645/kw/wndr3700%20v1) and grabbed the [manual in PDF](ftp://downloads.netgear.com/files/WNDR3700/Documentation/SM/WNDR3700_SM_04JUN2010.pdf) form. Since this was a refurb I didn't expect it to have the latest firmware installed, and it didn't, instead having some version 1.0.4, while the latest was 1.0.16. So I grabbed the [latest firmware](http://support.netgear.com/app/answers/detail/a_id/19565) for it, and flashed away, after a few minutes it rebooted, and I had version 1.0.16.98dnsNA installed. Then the fun began! 
<!-- more -->


### What is DD-WRT?

<img src="/assets/2011/dd-logo.png" align="right" border="0">On to [DD-WRT](http://dd-wrt.com/), what is it? According to their site, "<i>DD-WRT is a Linux based alternative OpenSource firmware suitable for a great variety of WLAN routers and embedded systems. The main emphasis lies on providing the easiest possible handling while at the same time supporting a great number of functionalities within the framework of the respective hardware platform used.</i>" So, instead of running the proprietary firmware that comes on the Netgear router, we want to run Linux, sounds good.

### What are DD-WRT's advantages over the stock firmware?


When you buy a piece of consumer electronics, you're basically bound by the rules of the software (referred to here as firmware). So if you buy a router from Linksys/Cisco, Netgear, D-Link, or others, you’re bound to their software, which limits what will and will not function on that hardware. From their perspective it makes some sense, you respect their limitations, and they promise to help with your problems. Of course when I buy something, it's mine, and if I wanted to jailbreak a phone, open a cable box (that says not to) or flash a 3rd party firmware on a router, that's what I'll do, partially for the adventure, and partially because I can. So what if it voids my warranty? If I can push the hardware to do more, it's worth it for me. So that’s where DD-WRT steps in. DD-WRT will allow all sorts of new bells and whistles to play with, that the proprietary firmare can hide away until you buy the more expensive version of the same hardware! Ya, it happens. Plus, since you're running Linux, you can use OpenSSH from just about any computer, login to the router and poke around. Want to add more software to it later? Sure. Want to setup some cronjobs? Go for it, the skys the limit


### Downloading the correct firmware version


Looking the site, it's clear that the router is fully supported, but to find the latest/greatest builds, you have to go through the [DD-WRT forums](https://secure.dd-wrt.com/phpBB2/). No big deal, it's just that with so much hardware to support, latest versions are updated frequently, so finding the right fit is it a bit of a guessing game, until you do some research on the forums. I found the right sequence of steps, so let's get started!

From the forum, the post [WNDR3700 Info is conflicting and fragmented](https://secure.dd-wrt.com/phpBB2/viewtopic.php?t=144665&highlight=) gives us what we need to know. jblack says


> Several people have reported the 17201 semi-bricking their router from an initial flash, but indicated if they flash build 16785, they could then use the "webflash" to update to 17201.
16785 can be found here: [ftp://ftp.dd-wrt.com/others/eko/BrainSlayer-V24-preSP2/2011/04-09-11-r16785/netgear-wndr3700/](ftp://ftp.dd-wrt.com/others/eko/BrainSlayer-V24-preSP2/2011/04-09-11-r16785/netgear-wndr3700/)

Use the one ending in NA if you are in North America.


So, visit [ftp://ftp.dd-wrt.com/others/eko/BrainSlayer-V24-preSP2/2011/04-09-11-r16785/netgear-wndr3700/](ftp://ftp.dd-wrt.com/others/eko/BrainSlayer-V24-preSP2/2011/04-09-11-r16785/netgear-wndr3700/  ) in a browser, and find the 'NA' version, which is netgear-wndr3700-factory_NA.img

Download it with curl:

    
    curl -O ftp://ftp.dd-wrt.com/others/eko/BrainSlayer-V24-preSP2/2011/04-09-11-r16785/netgear-wndr3700/wndr3700-factory_NA.img


...or wget:

    
    wget ftp://ftp.dd-wrt.com/others/eko/BrainSlayer-V24-preSP2/2011/04-09-11-r16785/netgear-wndr3700/wndr3700-factory_NA.img




### Upload the DD-WRT firmware to your router


Now, plug a cat5 cable directly into one of the ports on the router, and the other on a system - I usually use a laptop. Turn off wifi and make sure you can get an IP via DHCP from the router. Then, in a browser, navigate to the Netgear web console, usually at http://192.168.1.1 (default login is admin:netgear) and choose Maintenance > Router Upgrade

<div align="center"><img src="/assets/2011/router-upgrade.png" border="0"></div>

On the Router Upgrade page, click Browse, navigate to the firmware file wndr3700-factory_NA.img, click OK and finally Upload

<div align="center"><img src="/assets/2011/choose-image.png" border="0"></div>

At this point just be patient, go get a snack, and let the hardware and software do their thing. Do not get impatient and turn anything off, if you do, you might break the process (which isn't always the end of the world, as we'll find out later)


### I KAN HAZ SUCCESS?


If all went according to plan, your router will have rebooted, and when you refresh your web browser you should be prompted to change your admin username and password, which is an excellent first step for the new firmware! After doing that you'll end up with a page that looks something like this: 

<div align="center"><img src="/assets/2011/dd-wrt-starter_thumb.png" border="0"></div>

See all those tabs, Setup, Wireless, Services, Security, etc...these are all available for you to explore as you setup your new wifi router. Once you have the basics setup so it can function with your ISP, you can put it to work protecting your network.


### Not successful, or even... bricked?


What if something went wrong? You turned it off while you were flashing it, or like me, tried to update it to the next version while it was still plugged into your laptop, so it still didn't have internet connectivity? Ya, it happens, as it did to me, since I can rarely leave well enough alone! If you go by normal internet posts they'll say to hold down the 'RESET' button on the back, power it back on and see if it works. If it doesn't they'll say it's ruined, and there's no way to get it back. Of course once again the DD-WRT forums show us the way! A post from user Kyledoo titled [Atheros WiSOC based Hardware](https://secure.dd-wrt.com/phpBB2/viewtopic.php?t=145602&highlight=wndr3700):


> Did you put the router into recovery mode? Turn off the router with the power button, insert paper clip into reset hole, hold while turning on the router, hold for at least 45 seconds (I waited until the power light started blinking GREEN). This sucessfully unbricked my router, and I could then use tftp to flash the firmware onto it (I flashed dd-wrt build 16785)

Additional Information can be found here:

[http://www.dd-wrt.com/phpBB2/viewtopic.php?t=79802](http://www.dd-wrt.com/phpBB2/viewtopic.php?t=79802)


Following the steps found on [http://www.dd-wrt.com/phpBB2/viewtopic.php?t=79802](http://www.dd-wrt.com/phpBB2/viewtopic.php?t=79802) tells you how to download the latest firmware from Netgear (yes, going back to the proprietary one so your router will be 'like new' again). Hit this URL:  [http://kb.netgear.com/app/answers/detail/a_id/14244](http://kb.netgear.com/app/answers/detail/a_id/14244)

Find the download button and grab the latest. Now you need to assign your system a static IP:

    
    IP address: 192.168.1.2
    Subnet mask: 255.255.255.0
    Default gateway: <leave blank>
    Preferred DNS server: <leave blank>
    Alternate DNS server: <leave blank>


How to put your router in 'Recovery mode':


> Start by turning off the router using the power button on the back of the device. Now, using the small object of your choice, press and hold the "Restore Factory Settings", also known as the Reset button located on the bottom of the router (there is a red ring around it). While holding the rest button, turn the router back on. Continue to hold the reset button for approximately 45 seconds then release it.


and finally, how to use tftp (a small commandline ftp client present in Windows, Mac and Linux) that will copy the firmware to the router and initiate the recovery.

    
    tftp -i 192.168.1.1 put WNDR3700-V1.0.4.68NA.img


After this you do, you'll do the wait around game again, this time longer than before, but once it's rebooted and all the lights come back on, you'll see that you have the stock firmware installed, the router works, and is ready for you to try to install DD-WRT again.


### The Loopback problem


So the only problem I had after getting this router up and running was to have it do the normal loopback for me. So if I was in on my network and I wanted to hit [http://fak3r.com](http://fak3r.com) it would bounce me right back to the server, instead of going out into the wild internet. Apparently this is a known issue, with a [user proposed fix](http://www.dd-wrt.com/phpBB2/viewtopic.php?t=89353&highlight=local) that works!


> NAT Loopback fix for 15760 and higher, (Port forward issue) ... Save the following commands to the Firewall Script on the Administration->Commands



    
    insmod ipt_mark
    insmod xt_mark
    iptables -t mangle -A PREROUTING -i ! `get_wanface` -d `nvram get wan_ipaddr` -j MARK --set-mark 0xd001
    iptables -t nat -A POSTROUTING -m mark --mark 0xd001 -j MASQUERADE




### Parting shot


This is an awesome router, and DD-WRT seems to fit it like a glove. After running this setup for 3 weeks I'm completely sold on it. I'm not even going to mention it being 'rock solid' or 'always available', because it's running Linux, so it goes without saying. Give it a go on your own router, new or old, and have fun with it!

<div align="center"><img src="/assets/2011/60630_l.jpg" border="0"></div>
