+++
title = "HOWTO setup a very secure webserver"
draft = false
tags = [ "ssl", "h2", "http2", "nginx", "let's encrypt" ]
topics = [ "howto", "openbsd" ]
description = "The most secure way (that I currently know) to serve websites"
date = "2016-12-05T19:19:21-06:00"

+++
When getting started with Linux and open source software, running websites was one of the first things I learned how to do. Of course with the way software evolves, I'm still learning new ways to better secure, encrypt and protect web assests. Recently I wanted to build a new project and decided I wanted to use [OpenBSD](https://www.openbsd.org/), arguably the most secure operating system out of the box. While years ago I switched to [FreeBSD](https://www.freebsd.org/) for web and mailserver handling, OpenBSD is just more stringent about how it presents things. There's more to learn, sure, but that's all part of the fun. Now, if you look around at normal VPS options like DigitalOcean and Linode won't allow you to run OpenBSD, but with [Vultr](http://www.vultr.com/?ref=7051248-3B) (affilate link) you can use any ISO you can point to. They have a $5/month option, but they give 768M RAM verus the 512M that you get from most other VPS providers for that price. With that decided I ran through the install using their console and was up and running in no time. Now for the fun part, let's `ssh` to the server and setup a very setup a secure webserver.

<!--more-->
## Getting started

### Installing the  webserver

First we'll install our webserver, [NGINX](http://nginx.org/), which is all I've used for my personal projects for years, and it's what I recommend to my patients who chew gum. Instal NGINX on OpenBSD via the packages:

```
pkg_add nginx
```

Note that once you issue this command you're given an option to choose the flavor of NGINX available via the packages, and you should take the opporunity to install [NAXIS](https://github.com/nbs-system/naxsi). NAXIS defines itself as an "open-source, high performance, low rules maintenance WAF (web application firewall) for NGINX."


```
quirks-2.241 signed on 2016-07-26T16:56:10Z
Ambiguous: choose package for nginx
a       0: <None>
        1: nginx-1.10.1
        2: nginx-1.10.1-lua
        3: nginx-1.10.1-naxsi
        4: nginx-1.10.1-passenger
Your choice: 3
```

While projects like [mod_security](https://modsecurity.org/) promised WAF style defenses, the overhead (system and administration wise) always proved too heavy for my tastes. NAXSI claims that by default it, "reads a small subset of simple (and readable) rules containing 99% of known patterns involved in website vulnerabilities." There is an auto-learning setting so that you can have NAXIS taylor its rules to what your site actually sees, but for now we'll just take advantage of the default setup.

We'll come back to configure NGINX later when we have some more pieces in place.

### Installing an SSL certificate

While getting an SSL (secure socket layers) certificate on your website used to be expensive and complicated process, with projects like [Let's Encrypt](https://letsencrypt.org/) there's no reason not to use SSL. Let’s Encrypt not only offers free SSL certifcates, it's automated which makes it a snap to setup and renew certificates. The command to interact with Let's Encrypt is now called `certbot`, and it's available via OpenBSD packages, so let's install it: 

```
pkg_add certbot
```

To setup the initial certificate, you just need to run the command, point to the docroot of your website and provide the domain(s) you want the cert to protect. This is easy to do, so issue a command like the following, plugging in your domain in place of DOMAIN:

```
certbot certonly --agree-tos --webroot -w /var/www/htdocs -d DOMAIN -d www.DOMAIN
```

Since the certs are only good for 90 days, automating the renewal is key. First we'll test the renewal command:

```
certbot renew --dry-run 
```

This won't make any changes, but will show us if everything is setup and can be automated. If it's successful and you don't see any evil red text, you're good. Now we'll edit the `crontab` so `certbot` can check if the cert needs to be renewed, and then renew it if needed. 

```
30  5  1  *  *  /bin/sh /usr/local/bin/certbot renew --quiet
```

### Configuring the webserver

Now for my 'secret-sauce', we're going to use my long running project [nginx-globals](https://github.com/philcryer/nginx-globals.git) to lock down our NGINX and SSL configs far more than they are by default. (bonus, recently updated to support Let's Encrypt out of the box!) Checkout the repo:

```
git clone https://github.com/philcryer/nginx-globals.git     
```

Install the configs:

```
cp -R nginx-globals/globals /etc/nginx
```

The only additional step for the globals SSL setup is to deploy Diffie-Hellman for TLS, (as decribed [here](https://weakdh.org/sysadmin.html) by generating a `dhparams.pem` file: 

```
openssl dhparam -out /etc/nginx/dhparams.pem 2048     
```

Lastly we need to add the following to `/etc/nginx.conf`, make sure to replace the occurances of {{DOMAIN_NAME}} with your domain:

```
server {
        server_name                     {{DOMAIN_NAME}};
        root                            /var/www/htdocs/{{DOMAIN_NAME}};
        server_name_in_redirect         off;
        server_tokens                   off;
        return                          301 https://$server_name$request_uri;

}

server {
        listen                          443 ssl http2;
        root                            /var/www/htdocs/{{DOMAIN_NAME}};
        index                           index.html;
        server_name                     {{DOMAIN_NAME}};
        server_name_in_redirect         off;
        server_tokens                   off;
        ssl_certificate                 /etc/letsencrypt/live/{{DOMAIN_NAME}}/fullchain.pem;
        ssl_certificate_key             /etc/letsencrypt/live/{{DOMAIN_NAME}}/privkey.pem;

        include                         globals/cache.conf;
        include                         globals/drop.conf;
        #include                         globals/php.conf;
        include                         globals/secure.conf;
        include                         globals/ssl.conf;
}
```

Things to notice here: 

* on the listen line for the SSL port, we're going to run the `http2` option (you can read up on why that's a great thing [here](https://fak3r.com/2015/09/29/howto-build-nginx-with-http-2-support/))
* the `ssl_certificate` links to the new Let's Encrypt certs
* the include stanzas at the end to enable the `nginx-globals` goodness
* the commented out `globals/php.conf` since I'm not running php here, but it's there if you need it later

Test your configuration with the command `nginx -t` and follow any prompts to fix anything it complains about. Once that's complete we'll restart NGINX with the new hardened configs:

```
rcctl restart nginx
```

And finally enable NGINX so it will startup on boot:

```
rcctl enable nginx
```

## Conculsion

Now to test our webserver configuration and SSL setup, go and test it at the [SSL Server Test](https://www.ssllabs.com/ssltest/). You should expect to get a result similar to this:

<div align="center"><img src="/2016/ssllabs.png" width="450" height="200" alt="SSL labs results"></div>

## What else?

While this is as secure as I know how to do it, there's always  more to do and more to learn. For one thing we should setup OpenBSD's amazing firewall [pf](https://www.openbsd.org/faq/pf/) to protect the webserver. I'll be doing this soon and will enable some of the basic protection measures that `pf` can provide, so watch this space.  
