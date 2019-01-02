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

, I've settled on the this for my fastcgi config block within my lighttpd.conf file.<!-- more -->

    
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
