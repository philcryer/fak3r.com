---
author: phil
comments: true
date: 2007-11-07 13:15:10
layout: post
slug: allow-varnish-to-reuse-its-shared-object
title: Allow Varnish to reuse its shared object
wordpress_id: 643
categories:
- geek
tags:
- caching
- code
- features
- varnish
---

**![Varnish logo](http://fak3r.com/wp-content/uploads/2007/11/varnish-logo-red-64.gif)BACKGROUND**:**  **_The following is a proposal I submitted to the Varnish developers in order to make it simpler to integrate _[_Varnish_](http://varnish.projects.linpro.no/)_ (an HTTP accelerator for web sites) into production environments.  fak3r uses Varnish in front of its webserver, Lighttpd, so it's likely that the page you're now reading was served to you not by the webserver, but via Varnish_.

Currently Varnish requires a C compiler to be present on the machine it's running on, since it needs to compile the VCL config file into a shared object each time it starts. During shutdown, Varnish removes this shared object since it will be rebuilt during the next start. This routine repeats regardless of if anything has changed in the VCL config file, and serves as a road bump to getting Varnish into certain production environments since traditionally development applications (such as the C compiler) are not allowed in such instances. For now I am putting aside the arguments as to why it's is acceptable to have development applications in production instances, since that argument's outcome will vary in different situations, and I am aiming for a solution that will cover all instances.
<!-- more -->
My proposal is to allow Varnish to reuse its shared object, instead creating it during on launch, and destroying it on shutdown. The reuse function would be called if a flag was present during startup, either on the command line as -r, or as defined in the DAEMON_OPTS variable. This would cause Varnish to do two things differently than it does now:



	
  * upon startup it would not create a new shared object, rather it would search for, and use, the first shared object it found in the following location: `/var/lib/varnish/`hostname`/bin.*`

	
  * when called to shutdown, Varnish would not remove the existing shared object, thus leaving it to be reused, if called again with the `-r` flag


The shared object would need to be created (and recreated after any changes to the VCL file) on a clone machine that has a C compiler, and then installed to the production machine during a change window, which would be no different from a normal config file change. The upside of this would be that the C compiler requirement would be conditional upon the use of the reuse flag, which would allow smoother integration of Varnish into production environments.

