---
author: fak3r
comments: true
date: 2012-05-17 14:04:23
layout: post
slug: howto-create-a-normal-mysql-user
title: HOWTO create a normal MySQL user
wordpress_id: 3825
categories:
- geek
- linux
tags:
- database user
- linux
- mysql
- security
- sys admin
---

I found this online, and it's a perfect example of a bad habit I've been trying to clean up for some time. When I'm trying out software that needs a [MySQL](https://www.mysql.com/) database, I'm used to `create database foo;` but not creating a specific user for that database. Sure, if it's in the install steps it's easy to cut and paste, but otherwise root ends up accessing everything, and just like using root in Linux, that's not a good idea, security wise. So that's it, I won't be lazy with this anymore, for reference here's how to do it, just replace DB name, username and password and you're all set.

    
    mysql -u root -p
    create database `<em>DatabaseName`</em>;
    grant all on <em>`DatabaseName`.*</em> to <em>'UsersName'</em>@<em>'localhost'</em> identified by <em>'UsersDatabasePassword'</em>;
    flush privileges;


<!-- more -->

**Update**: thanks to 'Bacon' below, I've simplified this method from what is below - thanks!

    
    <del>mysql -u root -p create database <em>DatabaseName</em>; create user '<em>UsersName</em>'@'localhost' identified by '<em>UsersDatabasePassword</em>'; grant all privileges on <em>DatabaseName</em>.* to '<em>UsersName</em>'; flush privileges;</del>


What are your lazy admin tendencies? Let me know and I'll write up more of these clean-up diatribes.
