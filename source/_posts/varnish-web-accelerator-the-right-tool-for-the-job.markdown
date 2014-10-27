---
author: phil
comments: true
date: 2008-12-28 13:29:27
layout: post
slug: varnish-web-accelerator-the-right-tool-for-the-job
title: 'Varnish web accelerator: the right tool for the job?'
wordpress_id: 1284
categories:
- featured
- geek
- headline
tags:
- caching
- proxy
- varnish
---

![Varnish logo](http://www.fak3r.com/wp-content/uploads/2007/11/varnish-logo-red-64.gif)_**Note**: The following testing and writeup occurred in the Fall of 2007 following months of research and conjecture.  I repost it now because it was not originally posted publicly, and because the results are still a driving factor in how I architect systems for web production.  This week I am implementing Varnish to enhance an image server's ability to scale and serve images online._

**The Job**


A client's new 'enterprise' content management system proves to be far too slow to serve the multiple dynamic web sites that it's scheduled to handle, and a reverse proxy was recommended by the company that sells the CMS to remedy the issue.


**The Tool**


[Varnish](http://varnish.projects.linpro.no/) is an Open Source, state-of-the-art, high-performance HTTP accelerator. Varnish is targeted primarily at the FreeBSD 6+ and Linux 2.6 platforms, and will take full advantage of the virtual memory system and advanced I/O features offered by these operating systems. Unlike other reverse proxy solutions such as Squid, Varnish was written from the ground up to be a high performance caching reverse proxy, and has been praised for its speed, stability under load and use of system resources when compared to other solutions. At the client's site we have setup Varnish, as well as Squid as reverse proxy solutions, both pointing to the same backend CMS, which in turn talks to an Oracle database. After many stress tests, the pattern remained the same. Typical results can be seen in the following graph which shows the results of stress test of 2500 requests with 40 concurrent users. The first column shows the CMS on its own for a base reading, then with Squid as a reverse proxy, and finally and with Varnish. <!-- more -->




![Varnish, versus other options](http://www.fak3r.com/wp-content/uploads/2008/12/varnish-graph.gif)


After testing Varnish against other solutions for over a month, the following strengths and weaknesses have been observed:

**Pros**



	
  * 


Proven to be many times faster than other current offerings


	
  * 


Designed with current methodologies taking full advantage of features of the latest hardware and kernel software


	
  * 


Uses far less system resources, which cuts down on hardware need and costs


	
  * 


Is Open Source and actively developed


	
  * 


Is fast, fast, fast



**Cons**



	
  * 


Not as widely depoloyed or tested when compared to other offerings like Squid


	
  * 


The requirement of a C compiler on an enterprise production box may raise some eyebrows (check their FAQ for reasoning on this - and a proposed solution I've come up with)



**The Right Tool For The Job?**


The noise about Varnish is justified, it utilizes new programming methodologies, exploits the latest hardware and advanced features of the Linux and FreeBSD kernels in order to dramatically speed up performance of web sites. When utilized to serve dynamic web sites it will do this while actually requiring less hardware; it is that good. Its ease of use, stability under load and simple configuration make it a no brainier for anyone running a website. Varnish is the right tool for the job.
