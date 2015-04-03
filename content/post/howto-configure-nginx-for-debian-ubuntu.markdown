---
title: "HOWTO: Configure nginx for Debian / Ubuntu"
slug: "howto-configure-nginx-for-debian-ubuntu"
date: "2008-05-05T12:20:19-06:00"
author: "fak3r"
categories:
- geek
- linux
tags:
- apache
- lighttpd
- linux
- nginx
- unix
- webserver
---

[![nginx](http://www.fak3r.com/wp-content/uploads/2008/05/nginx_small.png)](https://calomel.org/nginx.html)**UPDATE: **I'm reworking my config blending in the security ideas found on [camomel.org](https://calomel.org/nginx.html) they're really thought things through on this, this should make for a very secure environment.

I'm always trying new software, and with the webserver I've moved from Apache 1.3 to 2.0 to 2.2, and then later I moved everything over to Lighttpd, which I've liked, save for some memory issues that popped up.  Now, enter a web server named [nginx](http://nginx.net/) (engine x), written by a Russian hacker.  It's already proved it's meddle by running some of the largest Russian sites for years now.  It has the [speed of Lighttpd](http://superjared.com/entry/benching-lighttpd-vs-nginx-static-files/), but with none of that memory weirdness, plus it uses a fraction of the CPU, so scaling should be smooth for highly visited sites.  It also does cool things like load balancing, reverse proxy, IMAP and POP proxy, etc, so I can see it being used in a variety of ways on a network.  It took me some time to understand [how to configure it](http://wiki.codemongers.com/Main), which was a case of me just making it harder than it really is, so I wanted to post it here.  Look for updates as we go along, but this is currently backing a Production site I manage.

    
    user					www-data www-data;
    worker_processes  			5;
    pid 					/var/run/nginx.pid;
    events {
    worker_connections 1024;
    }
    http {
    include				/etc/nginx/mime.types;
    default_type			application/octet-stream;
    log_format main 		'$remote_addr $host $remote_user [$time_local] "$request" '
    '$status $body_bytes_sent "$http_referer" "$http_user_agent" '
    '"$request_time" "$gzip_ratio"';
    access_log			/var/log/nginx/access.log  main;
    error_log			/var/log/nginx/error.log;
    sendfile 			on;
    tcp_nopush        		on;
    tcp_nodelay      		off;
    keepalive_timeout		65;
    gzip				on;
    gzip_http_version		1.1;
    gzip_vary			on;
    gzip_comp_level 		6;
    gzip_buffers			16 8k;
    #gzip_proxied			expired no-cache no-store private auth;
    gzip_proxied 			any;
    gzip_min_length			1000;
    gzip_types			text/plain text/html text/css application/json application/x-javascript
    text/xml application/xml application/xml+rss text/javascript;
    server {
    listen			80;
    client_max_body_size	50M;
    server_name 		server.domain.com;
    root 			/var/www;
    index  			index.html index.php;
    access_log  		/var/log/nginx/access.log  main;
    error_page   		500 502 503 504  /500.html;
    location = /500.html {
    root		/var/www;
    }
    location ~* ^.+.(jpg|jpeg|gif)$ {
    root		/var/www;
    expires         30d;
    }
    location ~ \.php$ {
    include /etc/nginx/fastcgi_params;
    fastcgi_pass 127.0.0.1:9000;
    fastcgi_index index.php;
    fastcgi_param SCRIPT_FILENAME /var/www$fastcgi_script_name;
    fastcgi_param QUERY_STRING $query_string;
    fastcgi_param REQUEST_METHOD $request_method;
    fastcgi_param CONTENT_TYPE $content_type;
    fastcgi_param CONTENT_LENGTH $content_length;
    }
    }
    }
