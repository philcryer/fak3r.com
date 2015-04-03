---
title: "HOWTO: Install Roundcube Webmail from SVN (was CVS) on FreeBSD"
slug: "howto-install-roundcube-webmail-from-svn-was-cvs-on-freebsd"
date: "2005-11-15T18:59:00-06:00"
author: "fak3r"
categories:
- General
tags:
- bsd
- email
- howto
---

**UPDATE3**: The FreeBSD port is keeping up with this project very well, the current one is only 5 days old! I highly recommend going this route unless you're a developer or someone who likes to get the bugs before anyone else! :) Thanks to Bernard for bringing this up.

**UPDATE2**: Roundcube now uses SVN ([Subversion](http://subversion.tigris.org/)) for version control instead of CVS, I’ve updated all effected instructions.

**UPDATE**: Looking at the download page for Roundcube, I see that _There’s also a Spanish version of Phil’s guide written by Daniel A. Rodriguez._ ¡se ha traducido eso es lejano hacia fuera, yo! You can find the link [here](http://www.softwarelibre.misiones.gov.ar/index.php?option=com_content&task=view&id=4&Itemid=3) , I hope that helps. I would like to see more translations of HOWTOs in general, I wish there was a good Firefox plugin to translate pages (well) on the fly.

In all my years of running a mailserver at home, I’ve used quite a few different webmail apps to find the balance of functionality and style that I’ve been craving. This includes Horde/IMP, Squirrelmail, IlohaMail, OpenExchange, Hula (as well as a couple of others) but all were either lacking functionality, style, or readiness (Hula I’m looking at you). A few months ago I stubled across [Roundcubemail](http://www.roundcube.net/), a webmail app which aims to provide an _application-like user interface_, which it does. When you can drag and drop mail to a folder, you know you’re dealing with something pretty new, and AJAX related. The smoothness and speed of refreshes when you switch folders (along with the ‘spinner’ at the top of the screen to give you needed feedback) is just a breath of fresh air.


![Roundcube Webmail](http://fak3r.com/wp-content/uploads/2006/08/rcube.jpg)


Try out the [demo](http://www.roundcube.net/?p=demo) to see what all the fuss is about if you’re looking for a LAMP based IMAP webmail solution. Time will tell, but this project’s out of the gate showing makes me think it’s going to be a great app. Let’s hope they keep the configuration and options to a sane amount, unlike other webmail apps (Horde/IMP, I’m looking at you), so they can keep their clean, non-bloated feel. Since I’ve installed it I’ve moved on to nightly rebuilds from CVS, so I thought I’d document the steps needed to run the latest CVS version of Roundcubemail on FreeBSD. While there is a FreeBSD port in the ports tree (mail/roundcube) it is far out of date, so the CVS version is recommended since there are so many changes happening day-to-day with it. Once running you should also join the [dev@lists.roundcube.net](http://www.roundcube.net/?p=mailinglists) mailing list to keep abreast of, and report any, bugs that need ironing out. This HOWTO assumes you have a webserver (www/apache2), a mail transport agent (mail/postfix), and an IMAP server (mail/dovecot) installed and working. I brought them up and tested them with Squirrelmail (mail/squirrelmail), since it’s almost as easy to setup and always “just works”. SO, without further babble, here’s my first (of many) HOWTOs on fak3r.com.


## **HOWTO**: Install Roundcubemail from <strike>CVS</strike> SVN on FreeBSD




NOTICE: This document assumes you already have a webserver running with PHP support, a mySQL database a Mail transport agent, an IMAP server and the Subversion client to check out the code.. My configuration consists of Apache2 (www/apache2), PHP 4 (lang/php-4), mySQL 4.x (database/mysql40-server), Postfix (mail/postfix) and Dovecot (mail/dovecot). Additionally this install was done on FreeBSD 6.0, but I see nothing specific that would stop the same procedure to allow Roundcubemail on 5.x, 4.x or even any Linux distribution. Feedback on this is welcome.




1) As root, change to the webroot of Apache

    
    <code>cd /usr/local/www/data-dist</code>


<strike>2) Login to the Sourceforge Roundcubemail CVS server (when prompted for a password, just press ENTER)</strike>

<strike>cvs -d:pserver:anonymous@cvs.sourceforge.net:/cvsroot/roundcubemail login</strike>

2) Checkout Roundcubemail from the Sourceforge SVN server (when prompted, choose ‘p’ to accept the encrypted key permanantly)

    
    <code>svn checkout https://svn.roundcube.net/trunk</code>


<strike>3) Change into the roundcubemail directory</strike>

<strike>cd roundcubemail</strike>

3) Move the roundcubemail directory to your webroot, remove the ‘trunk’ directory, and then change into the roundcubemail directory

    
    <code>mv trunk/roundcubemail .
    rm -rf trunk
    cd roundcubemail</code>


4) Set permissions of the temp and logs dir so that the web user can read/write to them

    
    <code>chown -R www:www temp logs</code>


5) Create a database for storage of Roundcubemail data, replace $PASSWORD with the password you want the roundcube user to use to access mySQL

    
    <code># mysql
    > create database 'roundcubemail';
    > GRANT ALL PRIVILEGES ON roundcubemail.* TO roundcube@localhost
    IDENTIFIED BY '$PASSWORD';
    > quit</code>


6) Import the inital Roundcubemail SQL

    
    <code># mysql roundcubemail < SQL/mysql.initial.sql</code>


7) Change into the config directory

    
    <code>cd config</code>


8) Copy the config *php.dist files to *.php

    
    <code>cp db.inc.php.dist db.inc.php
    cp main.inc.php.dist main.inc.php</code>


9) Modify the config files to suit your environment. In db.inc.php you only need to change the database definition line, add your password in place of $PASSWORD

    
    <code>$rcmail_config['db_dsnw'] = 'mysql://roundcube:PASSWORD@localhost/roundcubemail';</code>


Assuming your mailserver is running on the same physical box as the webserver, disable database caching

    
    <code>$rcmail_config['enable_caching'] = FALSE;</code>


define the host as localhost

    
    <code>$rcmail_config['default_host'] = 'localhost';</code>


define smtp as localhost

    
    <code>$rcmail_config['smtp_server'] = 'localhost';</code>


and increase the session lifetime from 5 to something more reasonable (optional)

    
    <code>$rcmail_config['session_lifetime'] = 30;</code>


Launch a web browser and point it to

    
    <code>http://some.url/roundcubemail</code>


Then login with a valid/existing IMAP username and password.

To debug problems just tail -f (or multitail if you’re cool like me) /var/log/maillog to see what’s happening behind the scenes. Consult the mailing lists for issues and feel free to give feedback below. Since this app is under heavy development I expect this HOWTO to change as the app does.

