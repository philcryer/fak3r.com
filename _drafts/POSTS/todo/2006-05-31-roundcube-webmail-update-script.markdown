---
author: phil
comments: true
date: 2006-05-31 17:19:00
layout: post
slug: roundcube-webmail-update-script
title: Roundcube Webmail update script
wordpress_id: 7
categories:
- General
tags:
- code
- howto
---

The [Roundcube Webmail Project](http://www.roundcube.net/) is moving along, and my [Roundcube HOWTO install](http://fak3r.com/articles/2005/11/15/howto-install-roundcube-webmail-from-cvs-on-freebsd) is one of the more popular ones on my site.  Today I updated to the latest SVN version, since they’ve recently moved from CVS to SVN for version control, and wrote a script to automate this so I can just run it nightly via cron.  Click on ‘Read more…’ to see the script; you _should_ be able to just cut/paste it, set the perms to 755, edit the variables at the beginning to suit your system and run.  Of course you’ll need [Subversion](http://subversion.tigris.org/) installed to checkout the code with this script.  Feedback is appreciated if you have any suggestions, or come across something that doesn’t work for you.





> 

>     
>     <code>#!/bin/sh
>     PATH=${PATH}:/bin:/usr/bin:/usr/local/bin
>     # Edit these variables to suit your system
>     WEBROOT="/usr/local/www/data"
>     RCWM_DIR="roundcubemail-svn"
>     WWW_USER="www"
>     WWW_GRP="www"
>     #
>     test -d "${WEBROOT}/${RCWM_DIR}"
>     if [ $? -eq 0 ]; then
>     else
>     echo "ERROR: ${WEBROOT}/${RCWM_DIR} not found"
>     echo "  create it or redefine the variable in the script"
>     exit 1
>     fi
>     cd /tmp
>     svn checkout https://svn.roundcube.net/trunk
>     mv trunk/roundcubemail/* ${WEBROOT}/${RCWM_DIR}
>     rm -rf trunk
>     chown -R ${WWW_USER}:${WWW_GRP} ${WEBROOT}/${RCWM_DIR}/temp ${WEBROOT}/${RCWM_DIR}/logs/
>     test -f "${WEBROOT}/${RCWM_DIR}/config/main.inc.php"
>     if [ $? -eq 0 ]; then
>     else
>     echo "WARNING: ${WEBROOT}/${RCWM_DIR}/config/main.inc.php not found"
>     echo "  edit the existing main.inc.php.dist and rename sans .dist before running"
>     fi
>     test -f "${WEBROOT}/${RCWM_DIR}/config/db.inc.php"
>     if [ $? -eq 0 ]; then
>     else
>     echo "WARNING: ${WEBROOT}/${RCWM_DIR}/config/db.inc.php not found"
>     echo "  edit the existing db.inc.php.dist and rename sans .dist before running"
>     fi
>     exit 0</code>
> 
> 




