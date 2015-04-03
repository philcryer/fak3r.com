---
layout: post
title: "HOWTO serve Gandi SSL certs in nginx"
date: "2014-08-04T13:37:00-06:00"
comments: true
categories:
- howto
- ssl
- linux
---
I'm a big fan of [Gandi](http://gandi.net) for domain hosting since they are very transparent about their operation, support the efforts of [EFF](http://eff.org) and last but not least, are based in Paris. So today I transfered fak3r.com over to Gandi, and earned a free SSL cert for a year for doing so! Cool, since my old StartSSL cert expired, I needed to replace it, so this was nice timing. Now while Gandi's documentation is very good, and I've done plenty of SSL setup before, I still hit a snag that I've hit before, so this time I wanted to record it so I wouldn't have to look it up again next time. 
The issue comes about because [nginx](http://nginx.org) doesn't have a declaration for an intermediate, or chained cert file. So while [Apache](https://httpd.apache.org/) has the SSLCertificateChainFile directive, in nginx we have to concatenate it with the certificate file to have nginx recognize it. Here's how you do it.
<!--more -->
First we need a directory to hold the SSL certs, it's really not important where, but I've always put mine in /etc/nginx/ssl/FQDN

    mkdir -p /etc/nginx/ssl/fak3r.com

Then follow Gandi's instructions on creating the SSL data they need to make your cert, then download it to that directory. Next we need to grab Gandi's CA cert and put it in that directory too

    cd /etc/nginx/ssl/fak3r.com
    wget https://www.gandi.net/static/CAs/GandiStandardSSLCA.pem

Now we'll make a new file, first by catting your cert file (note: mine was named certificate-61332.crt - yours will be different) into a new one, here I named it after my domain with the .crt suffix

    cat certificate-61332.crt > fak3r.com.crt

Then, since their CA file doesn't contain a carriage return we need to echo one in before the CA information

    echo "\n" >> fak3r.com.crt

And now we cat in the CA cert information

    cat GandiStandardSSLCA.pem >> fak3r.com.crt

Now let's set permissions

    chown root:root fak3r.com.* *.key
    chmod 400 fak3r.com.* *key

Meanwhile in the nginx SSL block, be sure we've listed the location of our new certificate as well as our key from the the initial generation 

    ssl_certificate     /etc/nginx/ssl/fak3r.com/fak3r.com.crt;
    ssl_certificate_key /etc/nginx/ssl/fak3r.com/fak3r.com.key;

Test that everything works, and then if so, restart nginx to pickup the changes

    nginx -t
    /etc/init.d/nginx restart

Great, now that I have you here, why don't we cover some 'BONUS' material to make nginx serve SSL content a bit more securely. In your site configuration file, in your SSL section, besides the normal lines such as the location of the crt, key files and such, add some lines.

    server {
        [...]
        # Some configuration to avoid ssl timing attacks 
        ssl_session_cache     shared:SSL:10m;
        ssl_session_timeout   10m;
        # Some cipher configs to avoid BEAST and other vulnerabilties
        ssl_prefer_server_ciphers on;
        ssl_protocols  SSLv3 TLSv1 TLSv1.1 TLSv1.2;
        ssl_ciphers ECDHE-RSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-SHA384:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-SHA256:ECDHE-RSA-RC4-SHA:ECDHE-RSA-AES256-SHA:DHE-RSA-AES256-SHA:RC4-SHA;
        # Some security headers configured with the help of https://securityheaders.com/
        add_header  Cache-Control "public";
        add_header  X-Frame-Options "DENY";
        add_header  X-Frame-Options "SAMEORIGIN";
        add_header  X-XSS-Protection "1; mode=block";
        add_header  X-Content-Type-Options "nosniff";
        add_header  Content-Type "text/html; charset=UTF-8";
        add_header  X-Permitted-Cross-Domain-Policies "master-only";
        add_header  Strict-Transport-Security "max-age=31536000; includeSubDomains";
    }

And then, as always, test and restart nginx to pickup the changes

    nginx -t
    /etc/init.d/nginx restart

Now your SSL serving by nginx is far more secure than before. Look online for more tips, and share what you find. Until then, this is fak3r, keeping it secure.
