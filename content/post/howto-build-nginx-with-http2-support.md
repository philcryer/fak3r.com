+++
title = "HOWTO build nginx with http2 support"
Description = "nginx now supports HTTP/2, here's how to enable it"
date = "2015-09-29T22:27:42-06:00"
Categories = ["howto", "geek"]
Tags = ["nginx", "http2"]

+++
Last week [nginx](http://nginx.org) relased mainline version 1.9.5 which features [experimental HTTP/2 module](http://nginx.org/en/docs/http/ngx_http_v2_module.html).

## Required software

### OpenSSL

Like the earlier 
speed up.. https://en.wikipedia.org/wiki/SPDY

now…
 accepting HTTP/2 connections over TLS requires the “Application-Layer Protocol Negotiation” (ALPN) TLS extension support, which is available only since OpenSSL version 1.0.2


```
# cat /etc/issue.net; openssl version
Debian GNU/Linux stretch/sid
OpenSSL 1.0.2d 9 Jul 2015
```

### Build and app software

```
apt-get -y install build-essential zlib1g-dev libpcre3 libpcre3-dev libbz2-dev libssl-dev tar unzip curl
```

## nginx

```
wget http://nginx.org/download/nginx-1.9.5.tar.gz
tar -xzvf nginx-1.9.5.tar.gz
cd nginx-1.9.5
```

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

(~30 seconds…)

Next, we can compile the source:

```
make 
```

(~ 3mins…)

```
make install
```

```
root@scw-4d84f1:/etc/nginx# nginx -V
nginx version: nginx/1.9.5
built by gcc 5.2.1 20150911 (Debian 5.2.1-17)
built with OpenSSL 1.0.2d 9 Jul 2015
TLS SNI support enabled
configure arguments: --prefix=/usr/share/nginx --sbin-path=/usr/sbin/nginx --conf-path=/etc/nginx/nginx.conf --pid-path=/var/run/nginx.pid --lock-path=/var/lock/nginx.lock --error-log-path=/var/log/nginx/error.log --http-log-path=/var/log/nginx/access.log --user=www-data --group=www-data --without-mail_pop3_module --without-mail_imap_module --without-mail_smtp_module --without-http_fastcgi_module --without-http_uwsgi_module --without-http_scgi_module --without-http_memcached_module --with-http_ssl_module --with-http_stub_status_module --with-http_gzip_static_module --with-http_v2_module
```


SSL:
https://www.digitalocean.com/community/tutorials/how-to-create-a-ssl-certificate-on-nginx-for-ubuntu-12-04

mkdir /etc/nginx/ssl
cd /etc/nginx/ssl
openssl genrsa -des3 -out server.key 2048
openssl req -new -key server.key -out server.csr
cp server.key server.key.org
openssl rsa -in server.key.org -out server.key

openssl x509 -req -days 365 -in server.csr -signkey server.key -out server.crt


curl http://http2.github.io/http2-spec/index.html -o /var/www/html/index.html
chown -R www-data:www-data /var/www/html/index.html


cd /etc/nginx

vi nginx.conf



    # HTTPS server
    #
    server {
        listen       443 ssl http2;
        server_name  localhost;

        ssl_certificate /etc/nginx/ssl/server.crt;
        ssl_certificate_key /etc/nginx/ssl/server.key; 

        ssl_session_cache    shared:SSL:1m;
        ssl_session_timeout  5m;

        ssl_ciphers  HIGH:!aNULL:!MD5;
        ssl_prefer_server_ciphers  on;

        location / {
            root   /var/www/html;
            index  index.html index.htm;
        }
    }


nginx -t

nginx

,,


hit it in a browser…

24.107.156.217 - - [30/Sep/2015:03:02:08 +0000] "GET / HTTP/2.0" 200 357616 "-" "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:40.0) Gecko/20100101 Firefox/40.0"



curl it!

(       --http2
              (HTTP)  Tells  curl to issue its requests using HTTP 2. This requires that the underlying libcurl was built to
              support it. (Added in 7.33.0))


```
# curl -I -L -k --http2 --verbose https://212.47.231.177
* Rebuilt URL to: https://212.47.231.177/
*   Trying 212.47.231.177...
* Connected to 212.47.231.177 (212.47.231.177) port 443 (#0)
* found 180 certificates in /etc/ssl/certs/ca-certificates.crt
* found 720 certificates in /etc/ssl/certs
* ALPN, offering h2
* ALPN, offering http/1.1
* SSL connection using TLS1.2 / ECDHE_RSA_AES_128_GCM_SHA256
* 	 server certificate verification SKIPPED
* 	 server certificate status verification SKIPPED
* error fetching CN from cert:The requested data were not available.
* 	 common name:  (does not match '212.47.231.177')
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
> Host: 212.47.231.177
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
* Connection #0 to host 212.47.231.177 left intact
```
