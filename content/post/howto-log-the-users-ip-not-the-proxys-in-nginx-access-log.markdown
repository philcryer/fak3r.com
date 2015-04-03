---
title: "HOWTO: log the user's IP, not the proxy's, in nginx access log"
slug: "howto-log-the-user%e2%80%99s-ip-not-the-proxy%e2%80%99s-in-nginx-access-log"
date: "2008-12-18T12:55:27-06:00"
author: "fak3r"
categories:
- geek
- howto
tags:
- debian
- howto
- lighttpd
- nginx
- varnish
---

[caption id="attachment_780" align="alignright" width="150" caption="nginx"]![nginx](http://www.fak3r.com/wp-content/uploads/2008/05/nginx_small.png)[/caption]

So back in January I had a post about [HOWTO: log the user's IP, not the proxy's, in Lighttpd access log](http://www.fak3r.com/2008/01/09/howto-log-the-users-ip-not-the-proxys-in-lighttpd-access-log/), but today I switched that system to run [nginx](http://nginx.net) (actually nginx has been running since early this year, I just got lazy on running [Varnish](http://varnish.projects.linpro.no/)) fronted again by Varnish.  I had the same issue, but not much trouble solving it.  Since I often refer to my own notes on fak3r, I'm recording it here for myself, and anyone streaming in from Google.  So, as I talked about before, when you run a webserver behind Varnish doing http acceleration, the webserver access logs will display the IP of the proxy (generally 127.0.0.1) instead of the end user’s IP.  This not only breaks any kind of tracking or reporting you want to run against your webserver logs. Since this server runs Varnish in front of nginx, and it reveals the end user’s IP in the header as X-Forwarded-For, so it’s just a matter of making nginx use that variable in its access logs instead of the default variable defining the referring IP. Once we know that, the configuration is simple.  Edit your nginx.conf file:

    
    vi /etc/nginx/nginx.conf


Once in the file, find the block about logging, and add the following to it:

    
      log_format main '$remote_addr - $remote_user [$time_local] '
                    '"$request" $status $body_bytes_sent "$http_referer" '
                    '"$http_user_agent" "$http_x_forwarded_for"' ;
      access_log /var/log/nginx/access.log main;


and finally, restart nginx

    
    /etc/init.d/nginx restart


If you look at the logfiles now you'll see the IP of the original requester!
