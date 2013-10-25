---
author: phil
comments: true
date: 2009-05-26 22:23:35
layout: post
slug: file-system-full-but-why
title: File system full, but why?
wordpress_id: 1651
categories:
- geek
- linux
- rant
tags:
- adore-djatoka
- disk space
- disk usage
- djatoka server
- image server
- j2k
- JPEG2000
- linux
- unix
---

![0101010101](http://www.fak3r.com/wp-content/uploads/2009/01/0101010101.jpg)**UPDATE**: posted my workaround code below, good feedback already from Ryan (djatoka dev) and I'll be testing the proper fix on the server soon.
I've got a server that keeps filling up its disk space and failing to serve images after it gets to the _file system full_ error message.  First of all let me say, I don't blame it in the least, if the admin (aka me) doesn't do enough to secure the server enough disk space to do its job, I say, let me have it.  But after I've set the suspect daemon to use a *reasonable* amount of space I stopped thinking of it as the culprit, so when this issue arose again, I looked elsewhere for the cause.  Fast forward to today, the **server's file system filled up again**, and refused to serve any more data, again, I totally understand where the server is coming from, if it doesn't have enough disk space to do its job, it shouldn't have to apologize to anyone; it's all on the admin (again, aka, me), but what was going on?<!-- more --> So, after I finally figured things out, I was/am still a bit confused here, but to my defense, when I did an 'ls -ltrs /tmp' to look at directories of old cached files left over by [adore-djatoka](http://apps.sourceforge.net/mediawiki/djatoka/index.php?title=Main_Page) (which is the JPEG2000 (J2K) image server that I suspected of taking up all the disk space) :


> # ls -ltrs /tmp | grep temp-20090519-*
5.7M drwxr-xr-x 2 tomcat55 root     5.7M 2009-05-19 13:08 temp-20090519.130841


I concluded that the adore-djatoka server was innocent, and I felt bad for accusing it of the infraction since the largest directory I'd come across that it was responsible for was a paltry 5.7M.  Immediately I thought to myself, "..._these are not the droids you're looking for_", after all, this couldn't be responsible for a directory that was taking up almost 100G and making me look like a sophomore taking the "Intro To Computers" class for the second time, right?  But finally I got an unbiased opinion when I found a Unix utility called [ncdu](http://dev.yorhel.nl/ncdu), which is an **NC**urses **D**isk **U**tility ([du](http://en.wikipedia.org/wiki/Du_(Unix)) is an old school Unix utility which displays 'disk usage' of a selected directory), that, when run against the same directory as I scanned before, told me:


> # ncdu /tmp | grep temp-20090519-*
70.5GB [##########] /temp-20090519.130841


Now look, I'm no math wiz, but come on, WTF am I seeing wrong here?

[...]

So, long story short, the ncdu utility is able to delete the Gigs worth of files much quicker than my script, so the server now has plenty of disk space, and I now have a rotate script that I humorously call _tomcat_turnover_ that will:



	
  1. rotate out the old Tomcat temp directory (which is where the adore-djatoka server stores its cached images)

	
  2. create a new Tomcat  temp directory

	
  3. set the proper permissions on the new Tomcat temp directory

	
  4. restart Tomcat (and thus the adore-djatoka server)

	
  5. and finally, delete the contents of the old Tomcat temp directory, thus returning that used disk space to free disk space once again


So, now tell me why is this "my problem" and not instead handled by the sever, and why doesn't the adore-djatoka server respect the settings I set in djatoka.properties?


> # grep cache /var/lib/tomcat5.5/webapps/adore-djatoka/WEB-INF/classes/djatoka.properties
OpenURLJP2KService.cacheEnabled=true
#OpenURLJP2KService.cacheTmpDir=
OpenURLJP2KService.cacheSize=1000
OpenURLJP2KService.cacheImageMaxPixels=100000


Now, assumming _cacheSize=1000_ doesn't stand for 1000Gig, I've either found a bug in the djatoka software, which I'll post a bug report to the project to determine, or this is an error by the well meaning admin (aka me).  More info when I learn about it, and yes, I'll post my tomcat_turnover script here for extra credit next
The tomcat_turnover.sh workaround code:


> # wrapper to recycle tomcat, while taking care to clean the old
# temp directory independantly of restarting tomcat.

APP="tomcat5.5"
GROUP="root"
DIR="/var/lib/${APP}/temp"
DATE=.`date +%Y%m%d.%N`

# stop tomcat
/etc/init.d/${APPILCATION} stop

# shuffle directories
mv ${DIR} /tmp/temp${DATE}
mkdir ${DIR}
chown -R ${APP}:${GROUP} ${DIR}

# restart tomcat
/etc/init.d/${APPILCATION} start

# clean old temp
cd /tmp
nice -n 19 find temp${DATE} -atime 1 | while read x
do
rm $x
done
rm -rf temp${DATE}

# done
exit 0
