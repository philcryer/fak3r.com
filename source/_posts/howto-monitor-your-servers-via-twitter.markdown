---
author: phil
comments: true
date: 2010-04-12 19:41:03
layout: post
slug: howto-monitor-your-servers-via-twitter
title: HOWTO monitor your servers via Twitter
wordpress_id: 2203
categories:
- geek
- howto
- linux
tags:
- bash
- command-line
- commandline
- monitor
- operating system
- script
- server monitor
- sysadmin
- system administration
- tweet
- twitter
---

[caption id="attachment_2228" align="alignright" width="240" caption="Alert: your server has failed!"][![](http://fak3r.com/wp-content/uploads/2010/04/twitter_fail_whale.jpg)](http://fak3r.com/wp-content/uploads/2010/04/twitter_fail_whale.jpg)[/caption]

**UPDATE: **thanks to a reader's comment I looked into what it would take to get this working again since Twitter has completely disabled the old style of authentication in favor of full on OAuth. Basically a lot. To just post messages now it seems far more complex than it once was.My original idea with this was to do it as low tech as possible so users wouldn't have to install a ton of stuff and configure it - I wanted it to 'just work' easily. Now with OAuth it seems this will never work easily, first of all you have to '[register an application](http://dev.twitter.com/apps/new)' for it to have access to OAuth - which seems crazy to me, we don't want it to have access, just the ability to push a message to an account. Then if you look [there are libraries out there that *can* post](//apiwiki.twitter.com/OAuth-Examples), but [look at the directions](http://github.com/abraham/twitteroauth/blob/master/DOCUMENTATION) for one of the libraries, it involves not only building the app and getting temporary access to the twitter API, then you also have to get the two keys from that and bake them into the app by recompiling it, and then more configuration, etc. So **for now I am MARKING THIS IDEA AS DEAD**. If I figure out a new way to do it that I can sketch out I will, or if anyone else has a simple way post it in the comments and I'll update it here. Thanks for your interest and good luck!


<!-- more -->




The other day I got inspired to write a script that would allow me to monitor my servers via [Twitter](http://twitter.com/). The idea of having a column in [Tweetdeck](http://www.tweetdeck.com/) set aside to inform me of my servers' statuses' sounded cool, plus, it's quicker than checking email.  I know sending tweets from the command-line had been done before, but after seeing [briealeida's](http://unixsysadmin.org) post titled [Tweeting Cron Jobs](http://unixsysadmin.org/tweeting-cron-jobs/) I really got inspired.  While hers was written in [Perl](http://www.perl.org/), I didn't want to go that route since I had a few, self imposed, restrictions I wanted to stick to.  One, I only wanted to use standard shell commands, the ones you get by default in Linux, so you would have absolutely no dependencies to install for this to work.  Two, I wanted to see how much info I could stuff into a 140 character tweet, and still have it make sense.  While I'm still working on adding more info, the current state of the script gives me a quick snapshot of seven specifics metrics on a selected server, which I'm quite happy with.  To try it yourself only takes a few minutes.


First you'll need a Twitter account, either the one you already use, or a dedicated one that only your servers post to (this is what I've done).  For a tad bit more security I protected my tweets and made the account private - I don't think I'll get hacked by anyone that sees my system load is too high, but I'd rather not hear about it from security experts that I'm leaving myself open, so I'll continue to keep things blocked by default.  Also, I've setup the update method to use [SSL](http://www.openssl.org/) to login and transmit, so you can rest a little easier knowing that things are staying encrypted while they move over the wire.  The only option I've coded in is that you can use [wget](http://www.gnu.org/software/wget/) (by default) or [curl](http://curl.haxx.se/) (not installed by default in any [Linux](http://kernel.org/) distro I'm running, but is on [OS X](http://www.apple.com/macosx/) if someone wants it for that), so you have a little bit of flexibility if you need it.  Otherwise it's pretty much ready to go out of the box, define your username and password at the top of the script, chmod it, and away we go.  Slap it in your crontab  for daily/hourly updates, and the rest, as they say, will be handled by simple, beautiful, [BASH](http://www.gnu.org/software/bash/).

[codesyntax lang="bash" lines="no" container="pre_valid" capitalize="no"]

    
    #!/bin/bash
    #
    ###############################
    # twitter username/password #
    ###############################
    user="1user"
    pass="sekrit"
    #
    ###############################
    # run tasks for the report #
    ###############################
    HOST=`hostname -s`
    UP=`uptime | cut -d" " -f4,5 | cut -d"," -f1`
    LOAD=`uptime | cut -d":" -f5,6`
    PING=`ping -q -c 3 google.com | tail -n1 | cut -d"/" -f5 | cut -d"." -f1`
    MEM=`ps aux | awk '{ sum += $4 }; END { print sum }'`
    CPU=`ps aux | awk '{ sum += $3 }; END { print sum }'`
    if [ -x "/usr/bin/lsb_release" ]; then
    DIST="`lsb_release -s -i`/`lsb_release -s -c` on `uname -m`"
    else
    DIST="`uname -o` on `uname -m`"
    fi
    #
    ###############################
    # build the report for post #
    ###############################
    tweet="(HOST) ${HOST} (UP) ${UP} (CPU) ${CPU}% (MEM) ${MEM}% (LOAD) ${LOAD} (PING) ${PING}ms (DIST) ${DIST}"
    #
    ################################
    # check that post is <140 char #
    ################################
    if [ $(echo "${tweet}" | wc -c) -gt 140 ]; then
    echo "FATAL: The tweet is longer than 140 characters!"
    exit 1
    fi
    #
    ################################
    # post the report to twitter #
    ################################
    ### via wget (default)
    wget -q --user="${user}" --password="${pass}" --post-data=status="${tweet}" https://twitter.com/statuses/update.xml
    ### via curl
    #curl -k -u ${user}:${pass} -d status="${tweet}" https://twitter.com/statuses/update.xml >/dev/null 2>&1
    #
    ################################
    # if no errors, successful #
    ################################
    if [ $? -eq '0' ]; then
    echo "Tweet sent using `echo ${tweet} | wc -c`/140 characters."
    fi
    rm update.xml
    #
    ################################
    exit 0


[/codesyntax]

Told you it was pretty simple, now I get updates to my 'secret' server account on Twitter that look like this:


_(HOST) mookie (UP) 8 days (CPU) 6% (MEM) 44.1% (LOAD) 0.07, 0.06, 0.07 (PING) 29ms (DIST) Debian/squeeze on i686_


So what other statistics would be helpful to gather that we can tack on to what we already have?  Heck, we could even drop the (DIST) section, that just gives me a reminder if what distro each box is running; it could be shortened to just say Debian or [Slackware](http://www.slackware.com/).  One thing I'm working on, I would like a monitor to report when any partitions get over 90% full, I haven't been able to figure that one out yet without using an external app not installed by default, like [monit](http://mmonit.com/monit/).  So, is this helpful?  Can you think of better way to do any/all of the above, while fulfilling my basic requirements?  Does it blend?
