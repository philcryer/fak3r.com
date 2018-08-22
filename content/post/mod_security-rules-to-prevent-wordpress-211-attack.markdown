---
title: "mod_security rules to prevent Wordpress 2.1.1 attack"
slug: "mod_security-rules-to-prevent-wordpress-211-attack"
date: "2007-03-07T10:14:42-06:00"
author: "fak3r"
categories:
- General
tags:
- hacker
- security
---

[, simply update your httpd.conf with the following rules:

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
