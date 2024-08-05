---
title: "Monit monitors sites and alert users"
slug: "howto-use-monit-to-monitor-sites-and-alert-users"
date: "2010-04-10T11:20:06-06:00"
author: "fak3r"
categories:
- geek
- howto
- linux
tags:
- debian
- linux
- monit
- monitor
- sysadmin
- system administration
- web sites
---

<div align="left"><img src="/2010/04/monit_banner.png" boder="0" alt="monit"></div>Ok, I've used the process management software, [monit](http://mmonit.com/monit), since at least 2004, and it is simply an indespensible tool in my sysadmin cache.  Basically it watches a process, say like [Apache](http://httpd.apache.org/), and restarts it if it dies.  But wait, that's not all, it does tons of other things.  Want it to watch it and restart it at a certain time?  Sure.  How about if it uses 50% of system memory in 5 cycles (cycles are checks, 120 seconds by default)?  Yep, it'll take care of that.  How about watching a file and stopping a service and/or issuing an alert by email or web if the file's UID, permission, or whatever has changed?   No problem.  Disk space is greater than 90% on one partition you want an email to go out to the admin?  Easy.  Seriously, once you start using monit you'll be amazed at what you can cover, it's truly one of the best tools I've ever used, and of course it's GPL'd open source.

So, this week we had an issue where a some of our sites were down, and the monitor that watches them were internal to our network, and relied on some of the same resources; which is lees than ideal.  I have a remote server running at one of our partner's sites, so it's the perfect canidate to watch our sites from a 'real world' view.<!-- more --> I already had monit running on the server (it's literally one of the first things I install once I bring a server up) so it was simple enough to have it watch our sites, in [Debian](http://www.debian.org/) you just put a new file in /etc/monit/include with the syntax of what you want done, and then add the path as `include /etc/monit/include/service` in /etc/monitrc.  First in monitrc we list a global email address for the systems admin to get alerts:

    
    set alert sysop@fak3r.com


Then, in the new include file, telling monit to watch a site is simple, for example:

    
    check host fak3r.com with address fak3r.com
    if failed url http://fak3r.com
    timeout 10 seconds for 1 cycles then alert
    then alert


and we're good to go, anytime fak3r.com fails to load (with a 10 second timeout allowance) the sysop gets emailed about it.  Now this really just tells us if the service is up, so to make this a better we can add content check.  This way the check will have to be able to load the page, and then check for a content string to verify the site is *really* up, and working.  For me I'll add have it check for part of my byline, since it if finds it in the response we know the webserver, and the database server, is working.

    
    check host fak3r.com with address fak3r.com
    if failed url http://fak3r.com and content == "look out honey"
    timeout 10 seconds for 1 cycles then alert
    then alert


Great, this works, for me, but for my client they had other requirements.  Since we have many different sites, they want IT support to know about all of them, but only certain developers to know about certain other sites.

    
    check host fak3r.com with address fak3r.com
    if failed url http://fak3r.com and content == "look out honey"
    timeout 30 seconds for 1 cycles then alert
    alert it.support@company.com { connection, timeout }
    alert debbie.developer@company.com { connection, timeout }
    alert dave.developer@company.com { connection, timeout }


Meanwhile we may have other sites that other folks need to know about, but still have IT support be in the loop.  So for example:

    
    check host someothersite.com with address someothersite.com
    if failed url http://someothersite.com and content == "Welcome new  luser"
    timeout 30 seconds for 1 cycles then alert
    alert it.support@company.com { connection, timeout }
    alert daniel.developer@company.com { connection, timeout }
    alert william.webmaster@company.com { connection, timeout }


This way certain employees can know that something is wrong with their site, while not being alerted to sites they're not involved with, meanwhile IT support is getting all the notices since need to address outages on any sites.

Lastly I changed the email format to be a little more user friendly for support and the other users.  While I've grown fond of the simple/to the point alert system monit has by default, it's easy to format the message so they're a bit more specific, and easier to categorize.  In the top, global section of monitrc, we add something like:

    
    set mail-format {
         from: monit@somebigserver.com
         subject: [ $SERVICE ] $EVENT - $DATE
         message: This is an $ACTION: $DESCRIPTION [$SERVICE], tested remotely from $HOST }


That way the subject tells them the $SERVICE (in this case the URL to the site that is down) the $EVENT (such as a connection timeout) along with the all import time and $DATE.  They don't even have to open their email to know what's up.

So again, I've used monit for a long time, and I've always been amazed by its consistency and flexibility, not to mention it's stability.  In this case it was able to solve a longstanding support issue that would have otherwise needed to be run by a third party hosted solution.  Once configured monit "just works" and is an invaluable tool.  I can think of many a support calls I used to get in the middle of the night that could have easily been handled by this little marvel, but hey, live and learn.  In a future article I'll demonstrate how we can have monit alert us via [Twitter](http://www.twitter.com/), and why not, it's all online these days.

What have you done amazing with monit?  Are you like me in constantly re-tweaking monitrc to get more out of this dynamo?
