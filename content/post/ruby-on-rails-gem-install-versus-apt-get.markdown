---
title: "Ruby on Rails: gem install versus apt-get"
slug: "ruby-on-rails-gem-install-versus-apt-get"
date: "2009-11-18T11:09:35-06:00"
author: "fak3r"
categories:
- geek
- howto
tags:
- apt-get
- bsd
- config
- configuration
- gem install
- hacker
- howto
- linux
- redmine
- ruby
- ruby on rails
- update
---

![rails](http://fak3r.com/wp-content/uploads/2008/10/rails.png)**UPDATE:** Thanks to Ryan, Ant and Fern for the tips.  With that in mind I found an online [Slicehost tutorial](http://articles.slicehost.com/2009/4/9/debian-lenny-ruby-on-rails) that contained the steps and explained how to install ruby via apt-get, then get the latest rubygems, install that manually, ran gem to update itself, then run gem to install rails - as suggested.  The steps I took from that page:
<!-- more -->
On a _Debian Lenny_ system that does not have ruby, rubygems or rails installed on it yet:

`apt-get update
apt-get upgrade
apt-get install ruby-dev ruby ri rdoc irb libreadline-ruby libruby libopenssl-ruby sqlite3 libsqlite3-ruby libsqlite-dev libsqlite3-dev`

Once that completes without errors, make sure ruby is installed and ok:

`ruby -v`

Now download the latest rubygem (1.3.5 as of this post) from RubyForge http://rubyforge.org/frs/?group_id=126:

`wget http://rubyforge.org/frs/download.php/60718/rubygems-1.3.5.tgz`

Unpack it, change into the directory, run setup:

`tar xzvf rubygems-1.3.5.tgz
cd  rubygems-1.3.5
ruby setup.rb`

After that you'll see:

`RubyGems 1.3.5 installed`

Then it's suggested that you make a symlink to gem1.8 so you can run it as `gem`:

`ln -s /usr/bin/gem1.8 /usr/bin/gem`

Now make sure everything is up to date (even though we just installed the latest):

`gem update
gem update --system`

And finally - install rails:

`gem install rails`

After this you can check what gem has installed, and their version numbers:

`gem list`

And there you have it, more steps than I wanted, but now I know how to have a Debian system up to date, with Ruby, and then having rubygems handling all of the other ruby things that are better dealt with as gems.  As for systems I already have running in production in mixed enviroments?  I'll look to migrate those to properly configured installs in the future.  I guess for extra credit I should contact the maintainer of rubygems, and the associated gems, for Debian to get their side of the story, or maybe a solution they could put in place moving forward.

**Original post:**

I've been using [Ruby on Rails](http://rubyonrails.org/) on and off for [many years now](http://fak3r.com/?s=rails), and friends are always showing me new RoR apps to try out that look fly.  I can get things up and running fine, but it's when the time comes to update an app that I have issues; I seem to come to the fork in the road where apt-get doesn't have the latest version of Rails or some dendancy, and gem install is the proposed solution.  I worry that mixing the two updating procedures will mess things up, since I have seen this before in [Debian GNU/Linux](http://debian.org), as well as [FreeBSD](http://www.freebsd.org/) (I suspect it's me, and there's a right way to do it).  So, for example, today I noticed there was a new version Redmine a few days ago, so I update to the latest via SVN (the [suggested way of updating Redmine](http://www.redmine.org/wiki/redmine/Download)):

`# cd /opt/redmine-svn
# svn up
At revision 3076.`

Now I copy in the email.yml and database.yml from my working instance so this will use the same config:

`# cp /opt/redmine/config/database.yml /opt/redmine/config/email.yml config/
`
So far so good, let's rake it up:

`# RAILS_ENV=production rake db:migrate
(in /opt/redmine-svn)
Missing the Rails 2.3.4 gem. Please `gem install -v=2.3.4 rails`, update your RAILS_GEM_VERSION setting in config/environment.rb for the Rails version you do have installed, or comment out RAILS_GEM_VERSION to use the latest version installed.`

So here we are, crap, what version of Rails do I have installed via apt-get?

`# apt-cache showpkg rails | head -n3
Package: rails
Versions:
2.2.3-1 (/var/lib/apt/lists/ftp.debian.org_debian_dists_squeeze_main_binary-i386_Packages) (/var/lib/dpkg/status)`

Damn, so what version of Debian am I running?

`# cat /etc/issue.net
Debian GNU/Linux squeeze/sid`

Yep, the latest, testing branch.  So here I am, do I leave the apt-get world and start up gem install or what?  My hesitation is that this is my 'production' version of Redmine, and I don't really want to build out a sep install just to test my Rails updating, and if I do that, will the gem Rails install hose my current apt-get installed Rails anyway?  So this is the problem I've had since I started playing with Rails apps, and it's been about 3 years now ([fak3r.com was on Typo](http://fak3r.com/2006/04/06/howto-fix-login-issue-on-typo/) for almost a year).  I'm open to suggestions as to how others handle this, do you just install Debian and then not even use apt-get for Rails/Ruby stuff?  It seems that `gem install` always have the most up to date stuff, I'm just concerned that updating things that way will interfere with an `apt-get update; apt-get upgrade` of the main system later, particularly now that I'm already in the apt-get side.  Do I reinstall and go all gem install for just Ruby stuff, and apt-get just for the system?  How do people segment this?  There has to be a proper way that I'm missing.

Comments?
