---
title: "HOWTO sort web-server logs to find top users"
slug: "howto-sort-web-server-logs-to-find-top-users"
date: "2010-03-31T11:07:30-06:00"
author: "fak3r"
categories:
- geek
- howto
tags:
- apache
- awk
- bash
- cat
- nginx
- one line
- one liner
- script
- sort
- throttle
- top user
- uniq
- web server
- web traffic
- webserver
---

[ were.  While watching a logfile tail by at high speeds is always fun, we wanted to be able to sort the web-server access log and find top users, to be able to narrow down where the traffic was coming from.  While we don't want to block users that want to access our data, sometimes we need to throttle things back so one requester doesn't overwhelm all the available bandwidth and make the site unusable for others.  So after some playing around and digging on Google, we came up with a nice, succinct one liner to do this, here it is:

    
    cat /path/to/access.log | awk '{print $1}' | sort | uniq -c | sort -n | tail


<!-- more -->Recently it seems that piping cat output to other apps is all I do on my servers, and I'm ok with that!
