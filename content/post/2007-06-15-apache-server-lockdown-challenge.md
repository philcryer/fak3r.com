---
title: "Apache server lockdown challenge"
slug: "apache-server-lockdown-challenge"
date: "2007-06-15T12:20:02-06:00"
author: "fak3r"
categories:
- geek
- linux
tags:
- bsd
- security
---

 criticisms appreciated.

<!-- more -->
<pre># Basic settings
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
DirectoryIndex index.html

# HTTP and performance settings
Timeout 60
KeepAlive On
MaxKeepAliveRequests 100
KeepAliveTimeout 15
MinSpareServers 5
MaxSpareServers 10
StartServers 5
MaxClients 150
MaxRequestsPerChild 1000

# Access control
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
Alias /error/ T_ERROR

# MIME encoding
TypesConfig /etc/mime.types
DefaultType text/plain
AddEncoding x-compress .Z
AddEncoding x-gzip .gz .tgz
AddType application/x-compress .Z
AddType application/x-gzip .gz .tgz
AddType application/x-tar .tgz

# Logs
LogLevel warn
LogFormat "%h %l %u %t \"%r\" %>s %b \"%{Referer}i\" \"%{User-Agent}i\"" combined
LogFormat "%h %l %u %t \"%r\" %>s %b" common
LogFormat "%{Referer}i -> %U" referer
LogFormat "%{User-agent}i" agent
ErrorLog T_DOCROOT/logs/error_T_INSTANCE.log
CustomLog T_DOCROOT/logs/access_T_INSTANCE.log combined

# Modules
LoadModule mime_module modules/mod_mime.so
LoadModule dir_module modules/mod_dir.so
LoadModule log_config_module modules/mod_log_config.so
LoadModule cache_module modules/mod_cache.so
LoadModule access_module modules/mod_access.so
LoadModule alias_module modules/mod_alias.so
LoadModule auth_module modules/mod_auth.so
</pre>

That's it, the whole file only takes up 59 lines (w/o comments or empty lines). If we look at what was statically built into the httpd binary that RHEL4 distributes, it looks pretty slim too, so credit where credit is due:`

<pre>
# /usr/sbin/httpd -l
Compiled in modules:
core.c
prefork.c
http_core.c
mod_so.c
</pre>

`So fellow g33ks, what you say?`
