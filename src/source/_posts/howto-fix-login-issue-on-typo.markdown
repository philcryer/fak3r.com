---
author: phil
comments: true
date: 2006-04-06 14:58:00
layout: post
slug: howto-fix-login-issue-on-typo
title: 'HOWTO: Fix login issue on Typo'
wordpress_id: 27
categories:
- General
tags:
- code
- hacker
- howto
---

> **NOTICE**: Before you try this, see the update below - this could mess things up if you have more than one user, and you’re not trying to fix the Admin login


fak3r.com runs the latest (greatest?) [Typo code](http://www.typosphere.org/), but sometimes this leads to problems.  After an update a few days ago I could no longer login to the site, thus I couldn’t add stories, admin the site or anything.  The folks on the Typo-dev list had plenty of suggestions on how to fix it, but led me in the right direction when they just said to delete the user and then recreate it with a new password.  This worked, and I’m posting it here in case someone else can’t login to their Typo instance in the future.  Go into your database (mine is mySQL 4.x), switch to your Typo database and enter the following:




    
    <code>delete from users where id=1;</code>





Quit out, fire up your Typo site in a browser and create a new user - done.

**UPDATE**: Dave from the typo-dev mailing list thinks this isn’t a good idea - his comments:


> Think that’s a bad fix - if you have an auto-increment id field on the
users
table, then when you recreate the user he has a different id.
I expect that’s why you can’t edit any of your old posts.

The best fix is to use the console, find the old user

me = User.find(1)

in my case, then reset the password with a

me.password = me.password_confirmation = ‘sekrit’

me.save


Later Steven wrote in to talk more about how to get into console:


> It’s not a Ruby thing, it’s a Rails thing. >You’ll have seen it if you watched any of the >screencasts by DHH. In case you haven’t seen
any of them, go here:

[http://rubyonrails.com/screencasts](http://rubyonrails.com/screencasts)

this one in particular (or its earlier >incarnation) is the one that
lit a fire under most folks asses to check >out rails:

[http://media.rubyonrails.org/video/rails_take2_with_sound.mov](http://media.rubyonrails.org/video/rails_take2_with_sound.mov)

In a nutshell though, from the root of your application, run:

`script/console`

This will bring up an interactive shell
session with your
applications data and environment available.


Lastly, here’s the [Console manual for Ruby](http://wiki.rubyonrails.org/rails/pages/Console)
