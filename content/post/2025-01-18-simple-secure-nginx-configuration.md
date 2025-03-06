---
title: "Simple, secure nginx configuration"
description: 'From not losing your data to enabling greater security with immutable Backups, Wasabi Cloud offers a compelling and afforadable solution'
date: "2025-01-18T07:45:12-05:00"
Tags: ["nginx", "webserver", "lets-encrypt", "ssl"]
Categories: ["howto"] 
draft: false
---
<figure>
<div align="center" />
    <img src="/2025/nginx-20.png" alt="nginx webserver" /><br />
    <figcaption>The nginx webserver has been my favorite for many years</figcaption>
</div>
</figure>
 
I've been working with the [nginx](https://nginx.org/) webserver for over 20 years, this after years of running (what I considered bloated) [Apache](https://httpd.apache.org/), and the slightly improved [lighttpd](https://www.lighttpd.net/). For me, nginx has always focused on being simple, secure, and fast. Over the years they've had plenty of improvements and I've always kept up learning/relearning how to setup sites as securely, and again, simply, as possible.

For 2025 here's now I define my site's http, https, Let's Encrypt certificate paths, 301 redirect, and other settings - all with http2 enabled in the same configuration block. Hard to get simplier than that... so here's my current nginx config for [fak3r.com](https://fak3r.com) (you're soaking in it!)

## nginx version

I'm running the latest stable release of nginx in [Debian GNU/Linux](https://debian.org) (Bookwork, version 12), specific details:

```shell
> nginx -V
nginx version: nginx/1.27.4
built by gcc 12.2.0 (Debian 12.2.0-14)
built with OpenSSL 3.0.11 19 Sep 2023 (running with OpenSSL 3.0.15 3 Sep 2024)
TLS SNI support enabled
```

## nginx configuration file

And finally, the configuration file: `/etc/nginx/sites-available/fak3r.com.conf`

```shell
server {
    listen						80;
    listen						443 ssl;
    http2						on;
    server_name 				fak3r.com;
    root						/var/www/html/fak3r.com;

    # SSL settings
    ssl_certificate				/etc/letsencrypt/live/fak3r.com/fullchain.pem;
    ssl_certificate_key			/etc/letsencrypt/live/fak3r.com/privkey.pem;
    ssl_dhparam					/etc/letsencrypt/ssl-dhparams.pem;
    include						/etc/letsencrypt/options-ssl-nginx.conf;

    # SSL modern settings recommended by https://ssl-config.mozilla.org
    ssl_protocols       		TLSv1.2 TLSv1.3;
    ssl_ciphers         		HIGH:!aNULL:!MD5;
    ssl_ecdh_curve				X25519:prime256v1:secp384r1;
    ssl_session_cache			shared:le_nginx_SSL:10m;
    ssl_prefer_server_ciphers		off;
    ssl_stapling				on;
    ssl_stapling_verify			on;
    resolver					127.0.0.1;

    # Headers
    add_header					Strict-Transport-Security "max-age=63072000; includeSubDomains always";
    add_header					X-Frame-Options SAMEORIGIN;
    add_header					X-Content-Type-Options nosniff;
    add_header					X-XSS-Protection "1; mode=block";

    if ($scheme = http) {
    	return					301 https://$server_name$request_uri;
    }
}
```

## Wrap-up

To me this is exciting, I feel like nginx is always improving while fighting against feature creep and providing excellent security and performance. Of course we're never done, what tips to you see that I could add? Am I off base on something, is there newer/better ways to handle this? Let me know, best way to reach me currently is email (see 'Contact' page), or [Mastodon](https://mastodon.social/@fak3r). Thanks!
