---
title: "HOWTO: determine optimal fastcgi settings for Lighttpd"
slug: "howto-determine-optimal-fastcgi-settings-for-lighttpd"
date: "2008-02-28T11:50:58-06:00"
author: "fak3r"
categories:
- geek
- howto
tags:
- fast-cgi
- lighttpd
- lighty
- max_procs
- optimize fastcgi
- php
- php5
- php_fcgi_children
- webserver
---

![PHP Fast-cgi](http://www.fak3r.com/wp-content/uploads/2008/02/php-fastcgi.thumbnail.png)Anyone building a server with a [LAMP stack](http://en.wikipedia.org/wiki/LAMP_(software_bundle)) today has tons of options, mine have evolved to using Varnish -> Lighttpd -> Xcache -> PHP5 -> MySQL.  Once I had [Lighttpd (aka Lighty)](http://www.fak3r.com/?s=lighttpd) installed and running PHP pages I looked to optimize the configuration and push it as hard as possible for more speed.  Of course lately I've been getting unexplained slowdowns, with many instances of php5-cgi appearing to be taking up almost all of my available CPU on `top`.  Reading up on things it appears that I had max_procs, along with PHP_FCGI_CHILDREN, set far too high for the load I'm getting. When you start lighty it gives you the number of processes you've define, and then those in turn spawn the number of children you've specified.  While my settings were too high, they were really overshooting things when you take into account that I'm using Xcache (which provides PHP pre-caching) and Varnish (for HTTP acceleration).  So even though one of my dynamic sites that I'm working on to 'monetize' things is getting 700-800 hits each day, my caching strategy is taking the load away from the ever available Lighty.  Because of this, lighty has much less to do, so giving it a ton of processes to just sit there and eat memory until they're zombified is a waste.  After reading the [lighty FAQ](http://trac.lighttpd.net/trac/wiki/FrequentlyAskedQuestions#HowmanyphpCGIprocesseswilllighttpdspawn%3Cbr%3E%3C/a%3E) and other [posts specific to this](http://www.fak3r.com/wp-admin/%3Cbr%3E%3C/a%3Ehttp://www.nokarma.de/2007/1/22/ubuntu-lighttpd-and-php5-cgi), I've settled on the this for my fastcgi config block within my lighttpd.conf file.<!-- more -->

    
    ## Start an FastCGI server for php5 (needs the php5-cgi package)
    fastcgi.server    = ( ".php" =>;
        ((
            "bin-path" =>; "/usr/bin/php5-cgi",
            "socket" =>; "/tmp/php.socket",
            "max-procs" =>; 1,
            "idle-timeout" =>; 20,
            "bin-environment" =>; (
                "PHP_FCGI_CHILDREN" =>; "2",
                "PHP_FCGI_MAX_REQUESTS" =>; "10000"
            ),
            "bin-copy-environment" =>; (
                "PATH", "SHELL", "USER"
            ),
            "broken-scriptfilename" =>; "enable"
        ))
    )


Once I have this in place and running well for a few days I'll shift over to MySQL and show how I have configured and optimized that for performance.
