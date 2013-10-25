---
author: phil
comments: true
date: 2007-06-15 12:20:02
layout: post
slug: apache-server-lockdown-challenge
title: Apache server lockdown challenge
wordpress_id: 530
categories:
- geek
- linux
tags:
- bsd
- security
---

![Apache logo](http://fak3r.com/wp-content/uploads/2007/06/logo_apache.gif)One of my favorite things about being a Linux admin is the ability to specify how things are going to be executed on the servers. I've been running the [Apache web server](http://httpd.apache.org/) for over 10 years now ([1997](http://www.redhat.com/about/corporate/timeline.html)), so setting up a new environment is no big deal, but I wanted to take it farther and cut as much out of a base install as possible, which still having it do what I need. I started with a Google search and a blank file for my httpd.conf, and went from there. Some background, since this is a work project I have a few restraints. First, we're running on Red Hat Enterprise Server 4 with some pretty beefy hardware. Also, currently we ARE NOT building from source (something I usually do on my own Apache instances) since we're still working out support options, which limits what we can do down to the almighty httpd.conf. I've trimmed down my conf at home, but since we have a smaller and more specific set of tasks for Apache here, I wanted to trim it down to the bone. So far I've gone through the [Apache Security](http://www.apachesecurity.net/) site, where I found their chapter on [Installation and configuration](http://www.apachesecurity.net/download/apachesecurity-ch02.pdf) especially helpful. I followed their suggestion of starting httpd.conf as a blank file. Later I ran my newly created conf through an [Apache 2.0 Hardening Guide](http://xianshield.org/guides/apache2.0guide.html), and even combed through the [Apache HTTP Server Module guide](http://httpd.apache.org/docs/2.0/mod/) to be sure I wasn't using anything extraneous. Now I'm being a bit idealistic with this config I know, but again, it's for a specific purpose, and I don't need to worry about many other factors that would cloud the waters as far as providing more options. I've taken out any specific modules that need to be loaded as part of my work so as not to confuse things, but I've left in our token variables (those that start with a T_) that get substituted just before install, so the question is, is there anything else I could cut back on? Also, is there anything missing that could lock things down further that don't need to be installed separately? (ie- I'm not going to be installing mod_security...yet, but I'd like to). Read on to see my current 'locked down' config, all suggestions and (constructive?) criticisms appreciated.

<!-- more -->
`###################################################
### Basic settings
####################################################
Listen T_LISTEN
User T_USER
Group T_GROUP
ServerAdmin webadmin@server.net
UseCanonicalName Off
ServerSignature Off
HostnameLookups Off
ServerTokens Prod
ServerRoot "/etc/httpd"
DocumentRoot "T_DOCROOT"
PidFile T_PIDFILE
DirectoryIndex index.html`

```####################################################
### HTTP and performance settings
####################################################
Timeout 60
KeepAlive On
MaxKeepAliveRequests 100
KeepAliveTimeout 15
MinSpareServers 5
MaxSpareServers 10
StartServers 5
MaxClients 150
MaxRequestsPerChild 1000`

```####################################################
### Access control
####################################################
[directory /]
Options None
AllowOverride None
Order deny,allow
Deny from all
[/directory]
[directory T_DOCROOT]
Options FollowSymLinks -Includes -Indexes -MultiViews -ExecCGI
AllowOverride None
Order Allow,Deny
Allow from all
[/directory]
[Directory T_CGIROOT]
Options ExecCGI -FollowSymLinks
AllowOverride None
Order allow,deny
Allow from all
[/Directory]
ScriptAlias /cgi-bin/ T_CGIROOT
Alias /error/ T_ERROR`

```####################################################
# MIME encoding
####################################################
TypesConfig /etc/mime.types
DefaultType text/plain
AddEncoding x-compress .Z
AddEncoding x-gzip .gz .tgz
AddType application/x-compress .Z
AddType application/x-gzip .gz .tgz
AddType application/x-tar .tgz`

```####################################################
### Logs
####################################################
LogLevel warn
LogFormat "%h %l %u %t \"%r\" %>s %b \"%{Referer}i\" \"%{User-Agent}i\"" combined
LogFormat "%h %l %u %t \"%r\" %>s %b" common
LogFormat "%{Referer}i -> %U" referer
LogFormat "%{User-agent}i" agent
ErrorLog T_DOCROOT/logs/error_T_INSTANCE.log
CustomLog T_DOCROOT/logs/access_T_INSTANCE.log combined`

```####################################################
### Modules
####################################################
LoadModule mime_module modules/mod_mime.so
LoadModule dir_module modules/mod_dir.so
LoadModule log_config_module modules/mod_log_config.so
LoadModule cache_module modules/mod_cache.so
LoadModule access_module modules/mod_access.so
LoadModule alias_module modules/mod_alias.so
LoadModule auth_module modules/mod_auth.so`

```That's it, the whole #! only takes up 59 lines (w/o comments or empty lines). If we look at what was statically built into the httpd binary that RHEL4 distributes, it looks pretty slim too, so credit where credit is due:`

`````# /usr/sbin/httpd -l
Compiled in modules:
core.c
prefork.c
http_core.c
mod_so.c`` ```

`So fellow g33ks, what you say?`
