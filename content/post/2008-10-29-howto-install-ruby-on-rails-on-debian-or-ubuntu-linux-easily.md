---
title: "HOWTO: install Ruby on Rails on Debian or Ubuntu Linux easily"
slug: "howto-install-ruby-on-rails-on-debian-or-ubuntu-linux-easily"
date: "2008-10-29T17:07:59-06:00"
author: "fak3r"
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

[, first install the dependencies for good measure:<!-- more -->

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
