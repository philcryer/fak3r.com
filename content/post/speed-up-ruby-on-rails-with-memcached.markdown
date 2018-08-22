---
title: "Speed up Ruby-on-Rails with memcached"
slug: "speed-up-ruby-on-rails-with-memcached"
date: "2006-05-11T18:46:00-06:00"
author: "fak3r"
categories:
- General
tags:
- bsd
- hacker
- howto
---

 take advantage of it. Below are steps compiled from both sites, and used on my FreeBSD 6.0 server, but most of the steps should work as well in Linux. Read more for the steps.




First let’s get memcached installed

    
    <code>cd /usr/ports/databases/memcached/</code>


We only want it to get past the configure step before we modify code

    
    <code>make configure</code>


Now it’s time to modify the code (NOTE: the howto linked to above was specific to a FreeBSD issue, if using Linux you may not need to make this modification)

    
    <code>vi work/memcached-1.1.12/memcached.c</code>


Find this line

    
    <code>#include "memcached.h"</code>


Add the undef line below it and save

    
    <code>#include "memcached.h"
    #undef TCP_NOPUSH</code>


Now we want it to compile and install

    
    <code>make install</code>


Once that’s complete, we want to enable memcached in rc.conf

    
    <code>echo "memcached_enable="YES"" >> /etc/rc.conf</code>


Then we’ll start memcached

    
    <code>/usr/local/etc/rc.d/memcached.sh start</code>


Next we’ll install the ruby-memcache client

    
    <code>cd ../ruby-memcache/
    make install</code>


Finally we’ll modify our Ruby-on-Rails app’s environment to use memcache as its session store (make a backup first!)

    
    <code>cp config/environment.rb config/environment.rb.dist
    vi config/environment.rb</code>


Find the line that tells session_store to use the database instead of the file system

    
    <code>#config.action_controller.session_store = :active_record_store</code>


Modify it so it tells it to use memcached, and save

    
    <code>config.action_controller.session_store = :mem_cache_store</code>


Stop Typo, and then manually clear the cache

    
    <code>rake sweep_cache</code>


Now restart your Typo server, and you’re done! It should now be storing all session data via memcached instead of your database.

