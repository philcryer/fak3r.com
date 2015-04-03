---
title: "HOWTO install php5-fpm on Debian Squeeze"
slug: "howto-install-php5-fpm-on-debian-squeeze"
date: "2011-09-27T07:21:52-06:00"
author: "fak3r"
categories:
- howto
- linux
tags:
- debian
- dotdeb
- php
- php-fpm
- php5
- php5-fmp
- squeeze
---

[caption id="attachment_3298" align="alignright" width="281" caption="PHP5-FPM"][![PHP5-FPM](http://fak3r.com/wp-content/blogs.dir/12/files/elephpant_281_193.png)](http://fak3r.com/geek/howto/howto-install-php5-fpm-on-debian-squeeze/attachment/elephpant_281_193/)[/caption]

Once [PHP](http://www.php.net/) hit version 5.3, it started shipping with [PHP-FPM](http://php-fpm.org/), which is the new way to handle PHP requests when serving web content. Their site describes it as, "_PHP-FPM (FastCGI Process Manager) is an alternative PHP FastCGI implementation with some additional features useful for sites of any size, especially busier sites_", but this is being pretty modest when you consider the [host of improvements](http://php-fpm.org/about/) it brings over the old way of doing things when running PHP with an 'alternate' webserver such as [lighttpd](http://www.lighttpd.net/) or [nginx](http://nginx.net/). So, it sounds like a slam dunk, time to remove all the handwritten fastcgi-php scripts from /etc/init.d, update PHP and go to town, right? Not so fast hot shot, in [Debian](http://debian.org) Squeeze (stable) they don't include PHP-FPM with PHP 5.3 (reasons for this tend to fall in the '[not enough testing](http://bugs.debian.org/cgi-bin/bugreport.cgi?bug=603174)' variety), so we have to look elsewhere for a PHP with this (and other upgrades) enabled. This is exactly what the site and repo [Dotdeb](http://www.dotdeb.org/) is there for, and they have the updated PHP 5.3 that includes the FPM module. So, to get it installed on Debian Squeeze we first have to add the repo line to sources.list

    
    vi /etc/apt/sources.list


and add the lines:

    
    deb http://packages.dotdeb.org stable all
    deb-src http://packages.dotdeb.org stable all


Then update apt, and fetch the appropriate GnuPG key for the repo:

    
    apt-get update
    wget http://www.dotdeb.org/dotdeb.gpg
    cat dotdeb.gpg | sudo apt-key add -


Now, installing the lastest PHP is as easy as:

    
    apt-get install php5-cli php5-suhosin php5-fpm php5-cgi php5-mysql


Check your specific version and look for dotdeb in the response:

    
    # php -v
    PHP 5.3.8-1~dotdeb.2 with Suhosin-Patch (cli) (built: Aug 26 2011 09:36:27)


Next, notice a new php5-fpm file in /etc/init.d which you can use to start PHP-FPM:

    
    /etc/init.d/php5-fpm start


A quick netstat will show you where it is listening:

    
    # netstat -plunt|grep php
    tcp        0      0 127.0.0.1:9090          0.0.0.0:*               LISTEN      14834/php-fpm.conf


Now, for nginx to know to send php requests to it, it's as simple as before when you had to point it to the manually running fastcgi-php process. Edit your site listed in /etc/nginx/sites-enabled:

    
    location ~ \.php$ {
          fastcgi_pass 127.0.0.1:9090;
          fastcgi_index index.php;
          fastcgi_param SCRIPT_FILENAME /var/www$fastcgi_script_name;
          include fastcgi_params;
    }


And then restart nginx:

    
    /etc/init.d/nginx restart


Now make an index.php file in your doc root to be sure things are cool:

    
    vi /var/www/index.php



    
    <span style="font-family: Georgia, 'Times New Roman', 'Bitstream Charter', Times, serif; font-size: 13px; line-height: 19px;">Set permissions on the file:</span>



    
    chown -R www-data:www-data /var/www/index.php


And finally, view the file in web browser to see all of the abilities your PHP has now. The one I'm especially interested in is the lastest APC module for caching. Eventually we should see a more updated version in Debian, once more testing is complete, promised for Squeeze in the link above. Let me know how it worked for you!
