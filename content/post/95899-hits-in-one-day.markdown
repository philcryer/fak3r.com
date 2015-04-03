---
title: "95,899 hits in one day"
slug: "95899-hits-in-one-day"
date: "2005-10-17T19:32:00-06:00"
author: "fak3r"
categories:
- General
tags:
- bsd
- hacker
- tech
---

I'm still [posting on my Slashdot thread](http://slashdot.org/comments.pl?sid=165282&cid=13790268) about Friday's slashdotting of [fak3r.com](http://fak3r.com/) as well as learning what worked, and where the bottleneck occurred.  First of all the all important numbers; visits, pages, hits and transferred data for 2005-10-15, as reported by [Awstats](http://awstats.sourceforge.net/):




    
    <code>Date		Pages		Hits		Bandwidth
    10-14-2005	18092 		95899 		644.47 MB</code>





Holy smokes, 95,899 hits for the day while transferring almost a cd's worth of data.  Again, not a huge number for a colo'd webserver with a big audience, but for a home built rig behind a 384/1.5 ADSL line, pretty cool.  Other interesting data gathered was:




    
    <code>Operating Systems (Top 10)
    Operating Systems Hits Percent
    Windows 80112 63.6 %
    Linux 25319 20.1 %
    Macintosh 14305 11.3 %
    Unknown 5034 4 %
    FreeBSD 741 0.5 %
    Sun Solaris 278 0.2 %
    OpenBSD 37 0 %
    NetBSD 20 0 %
    WebTV 1 0 %</code>





This was expected since it's a tech site, but it was still nice to see Linux so well represented (when I hit the site from work I'm coming in via XP unfortunately)




    
    <code>Browsers (Top 10)
    Browsers Grabber Hits Percent
    Firefox No 84168 66.8 %
    MS Internet Explorer No 17268 13.7 %
    Safari No 9715 7.7 %
    Mozilla No 4509 3.5 %
    Opera No 4011 3.1 %
    Unknown ? 2867 2.2 %
    Konqueror No 1278 1 %
    Camino No 555 0.4 %
    Galeon No 405 0.3 %
    Netscape No 307 0.2 %
    Others 764 0.6 %</code>





Again, same disclaimer, but it would be nice if Firefox were the rule, and not the exception for the general public.

So what did I learn?  I learned that my FreeBSD 6.0 box is setup well enough to handle *at least* 100,000 hits a day.  I learned that using Apache2 -> mod_proxy -> lighttpd -> fastcgi powered by [Typo](http://typo.leetsoft.com/trac/) for blogging  is a good enough combination to easily handle the traffic thrown it's way.  Still, while my server was only running the Ruby process around 35%:




    
    <code>51469 fak3r         1  99    0 38632K 32832K RUN  14:50 34.47% ruby</code>





static pages were still taking ~60 seconds to refresh during peak load.  Thankfully my ssh tunnel held up, so I was watching top and tailing the logs in real time.  From this I could see that everything was setup to handle the traffic, with headroom to spare, expect for my ADSL line, which still performed as expected.  It was indeed the bottleneck, but the fact that pages were still being severed (albeit slowly) showed it handled the traffic and served pages to all that would wait for them.  In the future I will likely use my [OpenBSD](http://www.openbsd.org/) firewall running [pf](http://www.openbsd.org/faq/pf/) to limit the traffic to the websever to still allow internal clients the bandwidth to surf, but with the same configuration this would only decrease our numbers.  So, better test would have this box on a bigger pipe (Speakeasy has a 1.5/6.0 line, as well as T1 options) which probably won't happen in the confines of my home network, but would likely really push the server to its limits.  Perhaps one day, in a colo'd location, my new FreeBSD powered 4U server on a T1 will notice a spike in traffic; seconds after my recent post to Slashdot...
