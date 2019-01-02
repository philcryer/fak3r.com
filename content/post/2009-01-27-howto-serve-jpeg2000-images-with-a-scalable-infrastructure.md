---
title: "HOWTO: serve jpeg2000 images with a scalable infrastructure"
slug: "howto-serve-jpeg2000-images-with-a-scalable-infrastructure"
date: "2009-01-27T00:47:51-06:00"
author: "fak3r"
categories:
- geek
- howto
- linux
tags:
- apache
- bhl
- Biodiversity Heritage Library
- caching
- djatoka
- j2k
- jpeg2000 image server
- linux
- mobot
- mobot.org
- open source
- proxy solutions
- reverse proxy
- scalable architecture
- stable infrastructure
- tomcat
- varnish
---

, so here I'll cover my rationale and decisions I made to provide a scalable, stable infrastructure to provide the images as efficiently as possible.




When I started sketching out how I wanted to run djatoka, I knew I wanted it to provide security, caching for performance and scalability and fault tolerance.  Our server runs [Tomcat](http://tomcat.apache.org/), which I didn't want to be public facing.  Because of this I proxy Tomcat requests through [Apache](http://httpd.apache.org/) with the use of [ajp_proxy](http://tomcat.apache.org/connectors-doc-archive/jk2/common/AJPv13.html), the successor to the old mod_jk.  Initially I was using [nginx](http://nginx.org) in place of Apache, but after reading about all the functionality and performance improvements ajp_proxy offered, it was a no brainier; this is how to present Tomcat in a production environment.



<!-- more -->


**Security**






	
  * First and foremost, I don't believe Tomcat should directly serve the internet, from how I've used it in the past, and how I see it today, that is not its strong point.  It's a great servlet engine and is very flexible for development; so let's only expose that part to a proxy to be shown online.

	
  * Apache is an excellent webserver, but when configured properly it's a fine dynamic proxy that can safely deliver data from other down stream services.  It's with this that I used a combination of Apache modules to allow data from Tomcat to be online.

	
  * Apache's configuration is also a key aspect to its security.  Too often modules are enabled 'by default' which makes running a Linux/Apache server easy, but this is the wrong way to approach it.  Fortunately the Debian developers know this and have packaged Apache so that when it's installed, only the modules required to get the server running are enabled.  This way you only enable what you need, keeping exposure to the elements at a minimum.  mod_proxy, ajp_proxy - the first module allows Apache to act as a proxy, the second optimizes the way it talks to Tomcat and handles things like open connections, reusing existing connections and so on.  It's basically a manager providing a far more robust solution than just Apache talking to Tomcat alone.

	
  * Lastly there's Varnish (which we'll talk more about next) serving up data in front of Apache; this allows us many options, but the one in this category, security, is that it can stop bots and junk traffic, when it hits the server; thus this kind of noise doesn't get down to Apache, further isolating Apache from being exposing it to harmful or malicious traffic.




**Caching for performance and scalability**






	
  * I've worked with many caching/[reverse proxy](http://en.wikipedia.org/wiki/Reverse_proxy) solutions over the years, and saw the potential to really improve djatoka's performance with various levels of cache.  Reading online about scalability, many leading sites boast of using 3-5 layers of 'cascading caching' to provide greater performance out of the same hardware had they not gone that route.

	
  * Varnish is a relatively newcomer to the scene and bills itself as an HTTP accelerator.  I've had the opportunity to test Varnish head to head against the long time standard reverse proxy, [Squid](http://www.squid-cache.org/), in a true production environment.  With minimal configuration it's clear, Varnish is much, much faster.  Even when things aren't ideal (such as with our dealing with 1000s of tiles that may/may not be reused in the time they're in the cache) it's still a great improvement, while being a much lighter solution for the server with far greater features that allow great flexibility for future expansion.  In this situation it takes the brunt of the traffic away from Apache, so if Varnish can handle the request for an object, Apache doesn't have to be bothered, increasing its ability to serve a greater amount of traffic.

	
  * The following diagram shows my real world test utilizing a site that contained mainly static HTML and images.











	
  * The next level down is Apache, which includes the modules disk_cache, file_cache and mem_cache.  Utilizing these modules allows Apache to keep it's own reserve of frequently used objects which it can query against when it gets a request, taking the load this time off of Tomcat.  If Apache has an object it serves it up, and doesn't need to tax Tomcat, allowing Tomcat to take on greater load.  Also, to improve Apache performance there's no reason not to enable mod_deflate, the replacement for the old mod_gzip, which handles zipping files before being served, reducing the file size the client has to receive.

	
  * Lastly we have djatoka which handles its own stable of cache via Tomcat's temp directory.  At last check it had more than 20,000 image tiles and jpeg2000 files it had to choose from when a request comes in.  Of course if it doesn't have it in cache it can generate the image itself, then after serving it, deposits it in its own cache for future requests.  Size of the cache directory can be set within the djatoka configuration.

	
  * In the following diagram you can see the multiple levels of caching, and the path a request takes through the system




[




[Click for larger image](http://www.gliffy.com/publish/1587073/)




**Fault tolerance**






	
  * Currently I have Varnish handling the front end on port 81, so all traffic requesting images goes there first.  After comparing the request to its own cache, it will pass that request to on to Apache, but before doing that it checks Apache's health.  This way if Apache were to go down it would seamlessly serve the request to it's secondary backend instead, which in this case is Tomcat, bypassing an otherwise blocking service.

	
  * We also use the monitoring software [monit](http://mmonit.com/monit/), a server solution I've used for years, and can't recommend enough.  Monit's sole purpose is watch the server, check processes, restart a process if it stops, kill/restart a process if it takes up too much resources, as well as do trivial things like check available disk space, report if file permissions have changed, and other server duties usually relegated to an admin.  The great thing about monit is that once configured it just runs in the background, the only time I hear from it is if it takes action, or notices a change.  Once one of parameters tripped monit, it promptly emails me details of what it's found.  Of course it's trivial to set up monit to also alert a mobile device, a mailing list, or a team of admins.




**Roadmap / Code / Enhancements**






	
  * For future enhancements, our roadmap will always be [publicly viewable](http://www.mobot.org/gemini/Issues.aspx?pi=8&m=1), suggestions and feedback is always welcome.

	
  * I post all of my code on my [github page](http://github.com/philcryer), which includes my auto installer for djatoka, and any improvements to djatoka that I create will be exposed there as well.

	
  * Lastly, I will be revisiting fault tolerance over the next two weeks, as it's not acceptable to have a single point of failure in our chain of services.  Some of the things to be addressed will be backup services, backup servers and proxies with load balancing and health checking.  These steps will be covered in a future posting.




**Conclusion**





We're very excited to be one of the early adopters of djatoka, and thus far are very encouraged by it's ability and stability.  We now have a secure solution with multiple layers of defense against hostile traffic, our scaling problems are now a thing of the past, with caching and fault tolerance allowing multiple,flexible paths as we look to expand our operations.  The CPU usage on the server is very light, giving us plenty of headroom, as seen form the following graph, where red and green represent system and user load respectively, and yellow being idle:








Meanwhile, server load shows a similar pattern, with a similar amount of headroom; it's going to take a lot more traffic for this server to be straining for any reason.







**Feedback**








As always, I enjoy feedback and am happy to answer questions and give further advice if needed.  Leave a comment below or drop me a line at work.



Phil Cryer, BHL Developer
phil.cryer (at) mobot.org

Thanks.


[![Reblog this post [with Zemanta]](http://img.zemanta.com/reblog_e.png?x-id=3f9a0b86-2e12-402a-8fe3-f6ba9ea2e16c)](http://reblog.zemanta.com/zemified/3f9a0b86-2e12-402a-8fe3-f6ba9ea2e16c/)
