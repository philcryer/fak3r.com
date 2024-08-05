+++
title = "Proxy Nexus-IQ via NGINX with SSL"
Description = "An SSL enabled NGINX config to proxy Nexus-IQ"
date = "2018-01-19T14:45:15-06:00"
Topics = ["geek", "howto"]
Tags = ["nginx", "nexus-iq", "ssl", "nexus", "reverse proxy"]
#menu = "main"

+++

Today, working on a client project, I was strugglying to get their Apache configuration working with virutal hosts, fuctioning as a reverse proxy to a Nexus IQ server while providing SSL. Between different virtual hosts pointing to differnt port, while rewriting parts of it, I wouldn't get it to do everything I needed. After a few hours I took a different tact and figured it out quickly in [NGINX](https://www.nginx.com/). I've only used NGINX for personal projects (and most work projects) for the past 6 years or so... it's better, faster, more flexible, easier to config, more reliable... forget about it, for my money it is tremendous. 

Now while Sonatype's documentation for [proxying their Nexus server with NGINX](https://help.sonatype.com/display/NXRM2/Running+Behind+a+Reverse+Proxy) is good, their docs to [do the same for their Nexus-IQ server](https://help.sonatype.com/display/NXIQ/IQ+Server+Configuration#IQServerConfiguration-HTTPS/SSLConfiguration) is not. In fact it must have been written by someone else, because it says, in part, "_One option to expose the IQ Server via https, is to use an external server like Apache httpd or nginx [..] and numerous tutorials for this setup are available on the internet._" Well I'm here to tell you that there are not any complete tutorials that I found that would allow me to proxy Nexus-IQ with NGINX providing SSL, so after much discovery and testing, here is an SSL enabled NGINX config to proxy Nexus-IQ.

```
server {
    listen                      *:80;
    server_name                 nexus-iq-server;
    return                      301 https://$server_name$request_uri;
}

server {
    listen                      *:443;
    server_name                 nexus-iq-server;
    client_max_body_siz         1G;
    ssl                         on;
    ssl_certificate             /etc/ssl/certs/nexus-iq-server.crt;
    ssl_certificate_key         /etc/ssl/certs/nexus-iq-server.key;
    location / {
        proxy_read_timeout      60;
        proxy_redirect          off;

        proxy_pass_header       Server;
        proxy_cookie_path       ~*^/.* /;
        proxy_pass              http://localhost:8070/;

        proxy_set_header        Host $host;
        proxy_set_header        X-Real-IP $remote_addr;
        proxy_set_header        X-Forwarded-Proto $scheme;
        proxy_set_header        X-Forwarded-Host $server_name;
        proxy_set_header        X-Forwarded-For $proxy_add_x_forwarded_for;
   }
}
```

The missing bits were in that last block, the `location` block needed some addtional `proxy_*` options - but once these are in place it just works perfectly. Time to uninstall Apache globally!
