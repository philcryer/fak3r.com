---
layout: post
title: "HOWTO connect to SSH via SSL with sslh"
date: 2013-08-13 09:38
comments: true
categories: 
- howto
- geek
---
Since I'm in the commandline fulltime, SSH is an indispensable tool for 'getting things done' - heck, I even run it on my Android phone now so I can poke around there (haven't broken anything... yet), so when I'm traveling or at a client's site that doesn't allow outgoing ssh (port :22) we have a problem. In the past I've always mapped SSH to some port other than :22 to prevent drive-by brute forcing login attempts, so I've put it on :443 (which is rarely blocked for outgoing is connections), but now that I'm running this site with SSL, that is no longer an option. Yes, we could try out :8080 (Tomcat's port), :8443 (Tomcat's SSL port) or :8181 (Debian's old Tomcat port), but we'll always have a better chance to get out over :443. While I've read how this *might* be possible using the great <a href="http://haproxy.1wt.eu/">HAProxy</a>, that always seemed like overkill and begged for a simplier solution. Apparently there already was one, I had just never heard of it; <a href="http://www.rutschle.net/tech/sslh.shtml">sslh</a> is an applicative protocol multiplexer, that forward ports initially sent to :443 on to other needed ports. Their description on what it can do:

>Probes for HTTP, SSL, SSH, OpenVPN, tinc, XMPP are implemented, and any other protocol that can be tested using a regular expression, can be recognised. A typical use case is to allow serving several services on port 443 (e.g. to connect to ssh from inside a corporate firewall, which almost never block port 443) while still serving HTTPS on that port.

Sounds perfect, so I went to install and configure it, and it was easier than I expected so let's get started - first of all I found a schmatic that illustrates what's happening [<a href="http://blog.lazycloud.net/wp-content/uploads/2012/03/schema-sslh.png">source</a>]
<!--more-->
<img src="/assets/2013/schema-sslh.png" border="0" align="center" /><br />
The package is already in Debian's <a href="http://packages.debian.org/wheezy/net/sslh">Wheezy repo</a>, and likely others, so for me it was simple to get rolling:
<pre>apt-get install sslh</pre>
Now let's take a look at how to configure it and get it working. First we'll want to tell the webserver to listen on 127.0.0.1:443, instead of on all interfaces, 0.0.0.0:443, which it is usually doing by default. I use <a href="nginx.org">nginx</a>, so it was a matter of changing the following
<pre>listen              443 ssl;</pre>
to
<pre>listen              127.0.0.1:443 ssl;</pre>
Now we'll then test the nginx config to make sure we don't have any ttyyppos (it happens)
<pre>nginx -t</pre>
If all is good, we'll restart nginx so it'll start listening on 127.0.0.1 and not 0.0.0.0 
<pre>/etc/init.d/nginx restart</pre>
And then we can verify this with netstat
<pre># netstat -plunt | grep nginx | grep 443
tcp        0      0 127.0.0.1:443           0.0.0.0:*               LISTEN      15261/nginx</pre>
Now, if you're using Apache is a similar deal, in httpd.conf or apache.conf, change
<pre>Listen 443</pre>
to
<pre>Listen 127.0.0.1:443</pre>
and if you're using Virutalhosts in Apache, be sure those configs look like
<pre>VirtualHost 127.0.0.1:443</pre>
Now make sure you can still hit your https site, "It should work (tm)", and then we'll configure sslh. Start with 
<pre>vi /etc/default/sslh</pre>
We need to change two things, first set it so sslh will start, so change
<pre>RUN=no</pre>
to
<pre>RUN=yes</pre>
Makes sense, yes? Moving on we need to change the IP that sslh will be listening to for https :443 and ssh :22 connections, so we'll change this line
<pre>DAEMON_OPTS="--user sslh --listen 0.0.0.0:443 --ssh 127.0.0.1:22 --ssl 127.0.0.1:443 --pidfile /var/run/sslh/sslh.pid"</pre>
To another use another IP, I used the systems local IP (yours will be different)
<pre>DAEMON_OPTS="--user sslh --listen 10.10.0.110:443 --ssh 10.10.0.110:22 --ssl 127.0.0.1:443 --pidfile /var/run/sslh/sslh.pid"</pre>
Now we should be able to start sslh
<pre>/etc/init.d/sslh start</pre>
And test it from a remote host, pointing to your IP or Domain on :443
<pre>ssh -p 443 luser@${domainname}.${tld}</pre>
Did it work? Great! If not, rerun the command with ssh -v to debug the issue.

This opens up a lot of new options, from connecting to a local XMMP/jabber server to a more reliable way to conect home via OpenVPN, but also raises the question of security. I'll do some tests on that and report back on what is exposed by sshl to the outside when a client hits :443.
