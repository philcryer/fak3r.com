---
title: "Run secure, caching DNS server in chroot"
slug: "howto-run-a-secure-caching-dns-server-in-chroot"
date: "2012-03-14T21:50:17-06:00"
author: "fak3r"
categories:
- geek
- howto
- linux
tags:
- dns
- privacy
- server
- unbound
---

[ Unbound DNS

I want to run my own DNS server, while I've done this before it was always a one off that I never spent much time researching or implementing it as well, and securely, as I wanted. When I tried out [DNSCrypt](http://www.opendns.com/technology/dnscrypt/) from the [OpenDNS](http://www.opendns.com/) folks, I emailed them and asked if it was available to run on a server, and sure enough, they have it in their [Github repo](https://github.com/opendns/dnscrypt-proxy), ready to compile in Linux. So I knew it was time to take things more seriously so I could properly run DNSCrypt as part of my DNS stack. So, first up, before getting DNSCrypt (and later [DNSSEC](http://www.dnssec.net/)) I need to have a good install of a local DNS server to handle things, and I've setted on using [Unbound](http://unbound.net/). Unbound [bills itself](http://unbound.net/) as "_...a validating, recursive, and caching DNS resolver. [...] Unbound is designed as a set of modular components, so that also DNSSEC (secure DNS) validation and stub-resolvers (that do not run as a server, but are linked into an application) are easily possible. The source code is under a BSD License._" Besides that the project has ties to some OpenBSD people, which bodes well for its security focus, and even has an option to chroot the install in the configuration file. In this article I'll share how I've setup Unbound running as a local, caching, recurisve DNS server in chroot. The next article in this series will deal with implementing DNSCrypt, and later DNSSEC.<!-- more -->

My install is going to be in Debian 6.0 (Squeeze), but it should be identical for Ubuntu Linux users, and for any other Linux users, after the Unbound is installed. So first we'll install Unbound, it's as easy as...

    
    apt-get install unbound


Thrilling, I know! Next up lets modify unbound's config.

    
    vi /etc/unbound/unbound.conf


It's a long, well commented config file, but all you need to get started is:

    
    interface: 0.0.0.0
    interface: ::0
    port: 53
    access-control: 192.168.1.0/16 allow
    chroot: "/var/lib/unbound"
    username: "unbound"
    hide-identity: yes
    hide-version: yes
    harden-glue: yes
    harden-short-bufsize: no
    harden-large-queries: no


Notice we turned on the chroot option, and few other hardening options for security. Next edit the default config for unbound

    
    vi /etc/default/unbound


We'll set it to start up, and set the variable to use the new chroot path

    
    UNBOUND_ENABLE=true
    #DAEMON_OPTS="-c /etc/unbound/unbound.conf"
    DAEMON_OPTS="-c /var/lib/unbound/etc/unbound/unbound.conf"


Next we'll setup the chroot paths

    
    mkdir /var/lib/unbound/etc/
    mkdir /var/lib/unbound/var/run


We'll put the config file in place

    
    mv /etc/unbound.conf /var/lib/unbound/etc


And symlink the pidfile into place

    
    ln -s /var/lib/unbound/var/run/unbound.pid /var/run/unbound.pid


Finally set permissions on the directory to the unbound user

    
    chown -R unbound:unbound /var/lib/unbound


Now even though we have default/unbound defined right, unbound will not start, and it turns out the init.d script is the culprit. I poked around online and found [this bug for the package](http://bugs.debian.org/cgi-bin/bugreport.cgi?bug=579622) in Debian, but that's not the way I wanted to fix. The problem comes in the line that sets the PIDFILE, where it runs unbound-checkconf to test the config, but it fails to find the config, so it bombs out. I fixed it this way

    
    #PIDFILE=$(${DAEMON}-checkconf -o pidfile)
    PIDFILE=/var/run/${NAME}.pid


And now you can start it up

    
    /etc/init.d/unbound start


I set my laptop to use the server for DNS, and saw the difference the caching made

    
    $ dig www.slashdot.org | grep Query
    ;; Query time: 71 msec


Not bad, run it again, and bam, much quicker.

    
    [~] $ dig www.slashdot.org | grep Query
    ;; Query time: 1 msec


I've had this setup on my server for a week, and it's worked perfectly. In an upcoming HOWTO I'll improve the setup by running it through DNSCrypt, and later include DNSSEC in the stack. Stay tuned!
