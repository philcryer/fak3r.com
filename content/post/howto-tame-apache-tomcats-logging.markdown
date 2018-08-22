---
title: "HOWTO tame Apache Tomcat's logging"
slug: "howto-tame-apache-tomcats-logging"
date: "2011-09-21T17:01:24-06:00"
author: "fak3r"
categories:
- geek
- howto
- linux
tags:
- apache tomcat
- catalina
- catalina.out
- logging
- logrotate
- logrotate.d
- tomcat
---

[caption id="attachment_3248" align="alignright" width="240" caption="Apache Tomcat"][[/caption]

If you're like me, you've had to support [Apache Tomcat](https://tomcat.apache.org/) for a good chunk of your IT career, and it hasn't all been wine and roses. Typically Tomcat will work great in a development, or in a proof of concept environment, but when it comes time to put it in production and have it face some real traffic, well, you get complaints. Now, why do I have a picture of Tomcat on a messenger bag here? It's because I would like to put Tomcat in a bag...**and throw it in a river!** But, since I haven't done that (yet), I'll talk about recently when I had some tomcat servers pumping out hundred of MGs of logfiles that weren't being rotated quickly enough, filling up the log partition and causing alerts to go off. Now [logrotate](https://iain.cx/src/logrotate/) is supposed to handle things, and while it's defaults will generally work fine, any persistant error from tomcat will make the logs quickly eat up all your space and cause you grief, so it's best to set it up to handle them ahead of time. First take a look at:

    
    /etc/cron.daily/tomcat6


and set the number of days of logfiles to keep lower than 2 weeks:

    
    - LOGFILE_DAYS=14
    + LOGFILE_DAYS=7


Then look at the logrotate directory:

    
    /etc/logrotate.d


Which list log settings for the different applications it knows about. Mine ([Debian](http://debian.org) 6.0) didn't have one for tomcat6 so it was using its own default logic, so I added my own tomcat6 file there:

**UPDATE**: a prod instances was failing with this, what happens is tomcat rotates backups on its own, which makes a ton of catalina.out.1 and later catalina.out.3.2012-05-20.tar.gz, etc, so I wasn't catching the .gz files, and the partition was filling up. So now I'm rocking it this way:

    
    /var/log/tomcat6/*.out /var/log/tomcat6/*.log /var/log/tomcat6/*.gz {
        daily
        size 10M
        rotate 10
        missingok
        compress
        copytruncate
        delaycompress
        notifempty
        create 640 tomcat6 root
    }


As always - THIS SHOULD WORK(tm)

    
    <del>/var/log/tomcat6/*.out { daily size 10M rotate 10 missingok compress copytruncate delaycompress notifempty create 640 tomcat6 root }</del>


Most of these are reasonable defaults, with size and compress being the most important for what we want to accomplish. After this is done it's easy to test your rules to see if they'll function the way you expect:

    
    logrotate -d /etc/logrotate.conf


The -d is the trick here, it tells it to kick out debug output, and not actually make any changes, look through the output and see if everything seems cool. Then, if you want to force it to rotate logs, and get the same verbose output, run it again with a -v

    
    logrotate -v /etc/logrotate.conf


...and logrotate will do it's thing. Now every-time [cron](http://en.wikipedia.org/wiki/Cron) runs you'll know what to expect, and finally have control over those dreaded, horrible, logfiles from Tomcat. (ok, so I'm being a little dramatic, but come on!)
