---
author: phil
comments: true
date: 2008-10-29 17:07:59
layout: post
slug: howto-install-ruby-on-rails-on-debian-or-ubuntu-linux-easily
title: 'HOWTO: install Ruby on Rails on Debian or Ubuntu Linux easily'
wordpress_id: 1138
categories:
- geek
- howto
tags:
- clouds
- CouchDB
- debian
- debian gnu
- drupal
- gnu linux
- howto
- linux
- newrailsapp
- ruby
- ruby on rails
- rubygems
- server
- Typo
- ubuntu
- ubuntu linux
- WEBrick
- wordpress
---

[![](http://www.fak3r.com/wp-content/uploads/2008/10/rails1.png)](http://www.fak3r.com/wp-content/uploads/2008/10/rails1.png)In the early days of this blog I used to run it on [Typo](http://www.typosphere.org/projects/show/typo), which *was* a great [Ruby on Rails](http://rubyonrails.org/) blogging platform (at one time).  Unfortunately the project stalled (for years) and I ended up jumping ship after a few months of bugs and the ever crashing Rails server, WEBrick.  Yes, if you search [Netcraft](http://netcraft.com) you could see that was my *exposed* server at the time...not good! ;)  Now if you look, Typo is still kicking, and it *may* be a solid platform now, I hope it is, as I even contributed a ton of the achieved themes that live on in the 'Theme Garden' there.  But on I moved into the world of MySQL/PHP front end sites via great apps like [Drupal](http://drupal.org) and [Wordpress](http://wordpress.org), fast forward, Ruby on Rails is a mature platform now, and I am evaluating webapps at work, so I needed to install Rails on [Debian GNU/Linux](http://debian.org) (but of course these directions would work just as well in [Ubuntu Linux](http://ubuntu.com).  It's amazing simple, I took some steps from the [Ruby on Rails wiki](http://wiki.rubyonrails.org/rails/pages/Installation), first install the dependencies for good measure:<!-- more -->

`apt-get -y install ruby irb ri rdoc ruby1.8-dev build-essential`

Then install rubygems and rails:

`apt-get -y install rubygems rails`

Yep, that was easy.  Now create your first rails app to ensure things are working as they should be:

`rails newrailsapp
cd newrailsapp
script/server`

Then hit your server to see it live, hit it in your browser: [http://120.0.0.1:3000](http://120.0.0.1:3000).  Or, if you're like me, you're running it on a remote server, have it bind WEBrick to the IP that you use to access it, so in my case I quit out of WEBrick, and restarted it with:

`script/server --binding=192.168.1.8`

And then hit it via [http://192.168.1.8:3000](http://192.178.1.8:3000) Nice, so much easier than I remember it being.  While I'm posting here, I'll drop a few more links I want to follow, as if I use RoR on upcoming projects I'll need to investigate as we scale to the clouds!

[CouchDB with Rails](http://peepcode.com/products/couchdb-with-rails)

[REST on Rails](http://peepcode.com/products/rest-for-rails-2)
