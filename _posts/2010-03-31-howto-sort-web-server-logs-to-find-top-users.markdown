---
author: phil
comments: true
date: 2010-03-31 11:07:30
layout: post
slug: howto-sort-web-server-logs-to-find-top-users
title: HOWTO sort web-server logs to find top users
wordpress_id: 2184
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

[![](http://fak3r.com/wp-content/uploads/2010/03/wario.jpg)](http://fak3r.com/wp-content/uploads/2010/03/wario.jpg)The other day I came across a situation where a web-server was getting hammered, and we needed to know who the offend(ers) were.  While watching a logfile tail by at high speeds is always fun, we wanted to be able to sort the web-server access log and find top users, to be able to narrow down where the traffic was coming from.  While we don't want to block users that want to access our data, sometimes we need to throttle things back so one requester doesn't overwhelm all the available bandwidth and make the site unusable for others.  So after some playing around and digging on Google, we came up with a nice, succinct one liner to do this, here it is:

    
    cat /path/to/access.log | awk '{print $1}' | sort | uniq -c | sort -n | tail


<!-- more -->Recently it seems that piping cat output to other apps is all I do on my servers, and I'm ok with that!
