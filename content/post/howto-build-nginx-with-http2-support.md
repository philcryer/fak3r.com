+++
title = "HOWTO build nginx with HTTP 2 support"
Description = "Latest nginx builds have expermental support for HTTP 2 here's how to enable it"
date = "2015-09-29T22:27:42-06:00"
Categories = ["howto", "geek"]
Tags = ["nginx", "http2"]

+++

> **UPDATE 02-29-2016** a reader had issues getting this working, and after reproducing his issue I found that the `ssl_cipers HIGH:!aNULL:!MD5;` no longer works. Apparently sometime after I wrote this, the HTTP/2 specs were updated, and browsers followed suit. This [blog post](http://garthkerr.com/transport-security-for-http2-protocol-with-nginx/) tells us, "_According to the HTTP/2 specification, over TLS 1.2 HTTP/2 SHOULD NOT use any of the cipher suites that are listed in the cipher suite black list, [found here](https://http2.github.io/http2-spec/#BadCipherSuites)_" So now, we have to call out another cipher before the blacklisted ones `ssl_ciphers AESGCM:HIGH:!aNULL:!MD5` Thanks for the note Elias!

Last week [nginx](http://nginx.org) relased mainline version 1.9.5 which features [experimental HTTP/2 module](http://nginx.org/en/docs/http/ngx_http_v2_module.html). According to the [Internet Engineering Task Force](https://tools.ietf.org/html/rfc7540) "_HTTP/2 enables a more efficient use of network resources and a reduced perception of latency by introducing header field compression and allowing multiple concurrent exchanges on the same connection.  It also introduces unsolicited push of representations from servers to clients. This specification is an alternative to, but does not obsolete, the HTTP/1.1 message syntax.  HTTP's existing semantics remain unchanged._" You can get an idea of how HTTP/2 is better and faster on this [demo page](http://www.http2demo.io/) which shows the multiple connections making a significant difference.

**TL;DR** it's faster, backwards compatible and the new hotness (obviously).

<!--more-->

# Start

## Install required software

### OpenSSL

Like the [SPDY](https://en.wikipedia.org/wiki/SPDY) project from Google (which they will deprecate in 2016), HTTP/2 runs over SSL only. To accept connections over TLS it uses the Application-Layer Protocol Negotiation (ALPN) TLS extension which is available only since OpenSSL version 1.0.2. So the first thing is that you need a (very) current version of [OpenSSL](https://www.openssl.org/) installed. This was my first snag, as my server was running Squeeze, which was only running a 1.0.1 varient. My soltion was to upgrade to Stretch, after that, updating SSL gave me 1.0.2d, and we were ready to move on

```
# cat /etc/issue.net
Debian GNU/Linux stretch/sid
# openssl version
OpenSSL 1.0.2d 9 Jul 2015
```

### Build and app software

To build nginx from source we're going to need some general build software, Debian is awesome about that by packing the general ones in `build-essential`. There's a few other things we need, so we'll get it done with the following line.

```
apt-get -y install build-essential zlib1g-dev libpcre3 libpcre3-dev libbz2-dev libssl-dev tar unzip curl
```

## nginx

Now on to the star of the show, nginx, with the latest release of the mainline branch, 1.9.5.

```
wget http://nginx.org/download/nginx-1.9.5.tar.gz
tar -xzvf nginx-1.9.5.tar.gz
cd nginx-1.9.5
```

Now we'll configure the code, I used the basic Debian settings, but noticed I enable HTTP/2 with the flag `--with-http_v2_module`

```
./configure \
    --prefix=/usr/share/nginx \
    --sbin-path=/usr/sbin/nginx \
    --conf-path=/etc/nginx/nginx.conf \
    --pid-path=/var/run/nginx.pid \
    --lock-path=/var/lock/nginx.lock \
    --error-log-path=/var/log/nginx/error.log \
    --http-log-path=/var/log/nginx/access.log \
    --user=www-data \
    --group=www-data \
    --without-mail_pop3_module \
    --without-mail_imap_module \
    --without-mail_smtp_module \
    --without-http_fastcgi_module \
    --without-http_uwsgi_module \
    --without-http_scgi_module \
    --without-http_memcached_module \
    --with-http_ssl_module \
    --with-http_stub_status_module \
    --with-http_gzip_static_module \
    --with-http_v2_module
```

*~30 seconds*

Next, we can compile the source:

```
make
```

*~ 3 minutes*

And finally, install:

```
make install
```

Check and ensure we have the right version installed, and the http v2 module enabled:
```
root@scw-4d84f1:/etc/nginx# nginx -V
nginx version: nginx/1.9.5
built by gcc 5.2.1 20150911 (Debian 5.2.1-17)
built with OpenSSL 1.0.2d 9 Jul 2015
TLS SNI support enabled
configure arguments: --prefix=/usr/share/nginx --sbin-path=/usr/sbin/nginx --conf-path=/etc/nginx/nginx.conf --pid-path=/var/run/nginx.pid --lock-path=/var/lock/nginx.lock --error-log-path=/var/log/nginx/error.log --http-log-path=/var/log/nginx/access.log --user=www-data --group=www-data --without-mail_pop3_module --without-mail_imap_module --without-mail_smtp_module --without-http_fastcgi_module --without-http_uwsgi_module --without-http_scgi_module --without-http_memcached_module --with-http_ssl_module --with-http_stub_status_module --with-http_gzip_static_module --with-http_v2_module
```

## OpenSSL

To setup OpenSSL I didn't want to reinent the wheel, so here I take a good howto from [Digital Ocean](https://www.digitalocean.com/community/tutorials/how-to-create-a-ssl-certificate-on-nginx-for-ubuntu-12-04). Change anything you don't like, but the condensed steps are:

```
mkdir /etc/nginx/ssl
cd /etc/nginx/ssl
openssl genrsa -des3 -out server.key 2048
openssl req -new -key server.key -out server.csr
cp server.key server.key.org
openssl rsa -in server.key.org -out server.key
openssl x509 -req -days 365 -in server.csr -signkey server.key -out server.crt
```

## nginx config

Next configure nginx so it knows to use HTTP/2:

```
cd /etc/nginx
vi nginx.conf
```

By adding the follwoing block to the end of the file (but still before the `}`)

```
    # HTTPS server
    #
    server {
        listen       443 ssl http2;
        server_name  localhost;

        ssl_certificate /etc/nginx/ssl/server.crt;
        ssl_certificate_key /etc/nginx/ssl/server.key;

        ssl_session_cache    shared:SSL:1m;
        ssl_session_timeout  5m;

        ssl_ciphers AESGCM:HIGH:!aNULL:!MD5
        ssl_prefer_server_ciphers  on;

        location / {
            root   /var/www/html;
            index  index.html index.htm;
        }
    }
```

Test that the configs and paths are all good to go:

```
nginx -t
```

If there aren't any issues, start it up (we installed from source so there's no init or systemd scripts yet):

```
nginx
```

## Default webpage

Let's put up a test webpage on the server, might as well pull an HTTP/2 spec page for that:

```
curl http://http2.github.io/http2-spec/index.html -o /var/www/html/index.html
chown -R www-data:www-data /var/www/html/index.html
```

## Testing

Time test things out, hit the site in a browser by pulling up `https://XXX.XXX.XXX.XXX`, if you're watching the logs on the webserver you'll see something like this:

```
YYY.YYY.YYY.YYY - - [30/Sep/2015:03:02:08 +0000] "GET / HTTP/2.0" 200 357616 "-" "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:40.0) Gecko/20100101 Firefox/40.0"
```

Sweet, there's that `HTTP/2.0` designation! Next let's use `curl` to test it, at the same time we can see everything it's doing by looking through the headers: (notice, you'll need a curl version 7.33.0 or newer, otherwise it won't support the `--http2` flag):

```
# curl -I -L -k --http2 --verbose https://XXX.XXX.XXX.XXX
* Rebuilt URL to: https://XXX.XXX.XXX.XXX/
*   Trying XXX.XXX.XXX.XXX...
* Connected to XXX.XXX.XXX.XXX (YYY.YYY.YYY.YYY) port 443 (#0)
* found 180 certificates in /etc/ssl/certs/ca-certificates.crt
* found 720 certificates in /etc/ssl/certs
* ALPN, offering h2
* ALPN, offering http/1.1
* SSL connection using TLS1.2 / ECDHE_RSA_AES_128_GCM_SHA256
* 	 server certificate verification SKIPPED
* 	 server certificate status verification SKIPPED
* error fetching CN from cert:The requested data were not available.
* 	 common name:  (does not match 'XXX.XXX.XXX.XXX')
* 	 server certificate expiration date OK
* 	 server certificate activation date OK
* 	 certificate public key: RSA
* 	 certificate version: #1
* 	 subject: C=AU,ST=Some-State,O=Internet Widgits Pty Ltd
* 	 start date: Wed, 30 Sep 2015 02:54:20 GMT
* 	 expire date: Thu, 29 Sep 2016 02:54:20 GMT
* 	 issuer: C=AU,ST=Some-State,O=Internet Widgits Pty Ltd
* 	 compression: NULL
* ALPN, server accepted to use h2
* Using HTTP2, server supports multi-use
* Connection state changed (HTTP/2 confirmed)
* Copying HTTP/2 data in stream buffer to connection buffer after upgrade: len=0
* Using Stream ID: 1 (easy handle 0x7f696b50)
> HEAD / HTTP/1.1
> Host: XXX.XXX.XXX.XXX
> User-Agent: curl/7.44.0
> Accept: */*
>
* http2_recv: 16384 bytes buffer at 0x7f6970fc (stream 1)
* http2_recv: 16384 bytes buffer at 0x7f6970fc (stream 1)
* http2_recv: returns 208 for stream 1
< HTTP/2.0 200
HTTP/2.0 200
< server:nginx/1.9.5
server:nginx/1.9.5
< date:Wed, 30 Sep 2015 03:17:42 GMT
date:Wed, 30 Sep 2015 03:17:42 GMT
< content-type:text/html
content-type:text/html
< content-length:357468
content-length:357468
< last-modified:Wed, 30 Sep 2015 03:00:05 GMT
last-modified:Wed, 30 Sep 2015 03:00:05 GMT
< etag:"560b5035-5745c"
etag:"560b5035-5745c"
< accept-ranges:bytes
accept-ranges:bytes

<
* Connection #0 to host XXX.XXX.XXX.XXX left intact
```

And there you have it, full HTTP/2 support.

# Conculsion

It works, and while it's still experimental, it seems to be working. I'm going to set it up under my main server and see how it goes! Any questions? Hit the comments below and let me know!
