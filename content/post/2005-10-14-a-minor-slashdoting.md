---
title: "A minor Slashdoting!"
slug: "a-minor-slashdoting"
date: "2005-10-14T20:21:00-06:00"
author: "fak3r"
categories:
- General
tags:
- bsd
- hacker
- tech
---

This morning on [Slashdot](http://slashdot.org/) there was a story about [Ruby on Rails](http://www.rubyonrails.org/) and my comment turned out to the the [second post](http://slashdot.org/comments.pl?sid=165282&cid=13790268). I took the opportunity to plug this site...err...I mean used this site as an example of Ruby on Rails via Typo and suggested people take a look and try out the 'live search' to give the database a workout, and did they ever. Logfiles were just scrolling along, httpd was throwing up pages, Ruby was driving all database queries via fastcgi. Top showed Ruby pushing upwards of 18%, so I killed Hulaweb, which was eating more, and watched Ruby take over, running at 34% at one point:

    
    <code>51469 fak3r   1  99    0 38632K 32832K RUN  14:50 34.47% ruby</code>


During one of the peak load times a full page reload from my client took just over 60 seconds, which is a very long time, but the DSL was the bottleneck, not the server or any of it's processes. Tailing the logs I watched it continuously spitting out pages to other clients in the queue before me, so [Lighttpd](http://www.lighttpd.net/) was doing its job as it should, and it had plenty of RAM/proc overhead (even though I saw it peak around 34%! at times), so the delay was simply my home DSL (1.5/384 down/up); which performed adminrable condisering the abuse. Thanks [Speakeasy](http://speakeasy.net/)! Once things calmed down a bit three hours later (ruby was still using ~18%) I could hit pages and have them reload as if nothing was happening, database searches as well.

So, for some very *rough* numbers; my 'second post' to Slashdot occured at 9:15AM, and at roughly 12:15AM /var/log/http-access.log showed 50,000 mod_proxy requests. So, requests that Apache handled via mod_proxy to Lighttpd:

    
    <code>50,000 / 03 = 16666.6666 pageviews/hour</code>



    
    <code>16,666 / 60 = 277.7777- pageviews/minute</code>



    
    <code>277    / 60 = 4.629- pageviews/second</code>


So my home server that I built by hand, running behind a standard ADSL line, served up an average of ~5 pages per second for over three hours. Fuckin' A!  Later, at 6 hours (3:15PM):

mod_proxy calls (page requests)

    
    <code>grep 14/Oct /var/log/httpd-access.log | wc -l
    78741</code>


DB Calls

    
    <code>awk '/^"action"=>"search"/' /usr/local/typo/log/production.log
    grep Oct 14 production.log | wc -l
    13478</code>


Successful DB Results

    
    <code>awk '/^Completed/' /usr/local/typo/log/production.log
    | grep Oct 14 production.log | wc -l
    13478</code>


I'll do more numbers once I learn if the way I pulled them were accurate, but as it stands the box handled almost 80,000 page requests today, and the logs are still rolling. Stay tuned.
