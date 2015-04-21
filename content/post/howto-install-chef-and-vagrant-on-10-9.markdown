---
title: "HOWTO install chef and vagrant on 10-9"
slug: "howto-install-chef-and-vagrant-on-10-9.markdown"
date: "2013-10-28T14:21:00-06:00"
categories:
- howto
- geek
---
<p>O'hai! I did a clean install of Apple's <a href="https://www.apple.com/osx/">OS X 10.9, Mavericks</a>, so I had to reinstall Ruby, rubygems, chef, virtualbox and vagrant. Since this has been somewhat of a black art before, I wanted to document how it works; now. This is a quick and dirty reference, no explanations, but the results should get you up and running quickly.</p>

# Install

## rbenv

```
ruby -e "$(curl -fsSL https://raw.github.com/mxcl/homebrew/go)"
brew install rbenv
if which rbenv > /dev/null; then eval "$(rbenv init -)"; fi
```

<p>To make your shell use the rbenv set ruby each time, add the following to your `~/.profile`</p>

```
eval "$(rbenv init -)"
```

## ruby

```
brew install ruby-build
rbenv install —list
rbenv install 1.9.3-p448
rbenv global 1.9.3-p448
```

## rubygems

* Having fun yet? Ya, so the latest version is 2.1.7, check if that's still true before proceeding*

```
cd /tmp
wget http://production.cf.rubygems.org/rubygems/rubygems-2.1.7.tgz
tar -zxf rubygems-*
cd rubygems-*
sudo ruby setup.rb
echo "gem: --no-ri --no-rdoc" > ~/.gemrc</pre>
```

## Chef (and supporting software)

```
curl https://www.opscode.com/chef/install.sh | sed ’s/10.8/10.9’ | sudo bash
sudo gem install berkshelf
sudo gem install foodcritic
sudo gem install bundler
```

## VirtualBox

* The latest version of Vagrant doesn't support Virutalbox 4.3 yet, so we will use 4.2*
* Download and install [Virtualbox 4.2 for OSX](https://virtualbox.org/wiki/Download_Old_Builds_4_2)
* Download and install the [Virtualbox extension pack](http://download.virtualbox.org/virtualbox/4.2.18/Oracle_VM_VirtualBox_Extension_Pack-4.2.18-88780.vbox-extpack)

## Vagrant

* Download and install the [OSX version of Vagrant](http://downloads.vagrantup.com/tags/v1.3.1)
* Once installed, install some plugins

```
vagrant plugin install vagrant-butcher
vagrant plugin install vagrant-berkshelf
```

# Test

*Now, let's make sure all the pieces work together*

```
vagrant init
```

<p>Cut and paste the following two lines into the `Vagrantfile` somewhere above the last end line:</p>

```
config.vm.box_url = "http://cloud-images.ubuntu.com/vagrant/precise/current/precise-server-cloudimg-amd64-vagrant-disk1.box"
```

and

```
config.vm.network "private_network", ip: "192.168.50.4", :bridge => 'eth1'
```

<p>Finally...</p>

```
vagrant up
vagrant status
knife bootstrap 192.168.50.4 --ssh-user vagrant --ssh-password vagrant --sudo
vagrant ssh
```

You should now be in the new vagrant host, huzza!
