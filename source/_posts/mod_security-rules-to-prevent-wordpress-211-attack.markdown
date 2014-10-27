---
author: phil
comments: true
date: 2007-03-07 10:14:42
layout: post
slug: mod_security-rules-to-prevent-wordpress-211-attack
title: mod_security rules to prevent Wordpress 2.1.1 attack
wordpress_id: 446
categories:
- General
tags:
- hacker
- security
---

[![mod_security](http://fak3r.com/wp-content/uploads/2007/03/modsecurity-button.gif)](http://www.modsecurity.org/)Anyone hosting a Wordpress 2.1.1 install should upgrade or immediately prevent access to certain queries to prevent an attack described [here](http://wordpress.org/development/2007/03/upgrade-212/). If the server is running Apache with [mod_security](http://www.modsecurity.org/), simply update your httpd.conf with the following rules:

`<IfModule mod_security.c>
SecFilterEngine On
SecFilterDefaultAction "deny,log,status:412"`

`# RULES: Prevent Wordpress 2.1.1 attack
# http://wordpress.org/development/2007/03/upgrade-212/
SecFilter "ix="
SecFilter "iz="`

`[...]`

`</IfModule>`

And then restart Apache. Note that while this is an effective temporary workaround, upgrading is recommended.  Also, any install *other* than 2.1.1 is not effected.
