---
author: phil
comments: true
date: 2006-03-21 19:25:00
layout: post
slug: howto-replicate-backup-copy-or-move-a-mysql-database
title: 'HOWTO: replicate, backup, copy or move a mySQL database'
wordpress_id: 31
categories:
- General
tags:
- hacker
- howto
---

Sure, this is pretty basic, but I never had to do it before, and since I just had a request from a user (that’s a good thing) to bring their blog up to the latest [Typo](http://www.typosphere.org/)/svn version, I knew it was time to learn.  Since you have to do a ’_rake migrate_ on the  database to update Typo there’s a chance (usually a good one with bleeding edge Typo) that the database may be worse for wear on the other end of the migration.  So, better to do it on a backup/copy of the database.  So no big, but I had never done it and had to do some research to learn how.  Hopefully this HOWTO will help others figure it out quicker than I did.

First dump a specific database (I’ll call it sample for illistration purposes) enter the following:




    
    <code>mysqldump -u root -p sample > sample-dump.sql</code>





And enter your mySQL root user’s password (this better be different than your system’s root password, or I’m coming for a visit!)

Now you have your database in a flat file named sample.sql.  At this point you could backup, copy, move or archive this file, and then restore it using the next steps, or do what I needed to do, reimport it under a different database name.

First you need to create an empty database with the new name you want to use, so login to mySQL as your root user:




    
    <code>mysql -u root -p</code>





Enter your mySQL root user’s password and then create the new database:




    
    <code>create database sample-new</code>





Lastly change into your new database, and then tell it to import the data from the original into this new one:




    
    <code>use sample-new
    source sample-dump.sql</code>





Now point your webapp (in my case Typo) to use this ‘new’ database, and muck it up all you want, knowing the original is safe and sound (unless you make a ’_typo_’).
