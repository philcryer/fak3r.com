---
layout: post
title: "HOWTO get started with chef, librarian-chef and vagrant"
date: 2013-08-30 14:51
comments: true
categories: 
- geek
- howto
---
I've used [Chef](http://www.opscode.com/chef/) to automate environments before, and the more I work at different, large clients, the more I see the need to use it, or [puppet](https://puppetlabs.com/), for controlling/managing servers. Now, I don't want to start a flamewar here, but what's the deal with... no, just kidding, I technically have tshirts from both Chef and Puppet, so I'm as impartial as you can get! Since a new gig I have starting up soon promises to make me into the _top chef_ in the office, I thought it was time to dust off my skills with a new HOWTO to get Chef installed, using Vagrant, started and configured. Bonus, I found that it's actually easier than it used to be to get up to snuff, now it can be condensed into a few steps.
<ul>
	<li>Install needed software</li>
	<li>Configure Vagrant with a base box definition</li>
	<li>Configure Chef by adding cookbook with Librarian-chef</li>
	<li>Tell Vagrant about the new cookbook</li>
	<li>Build a vagrant instance, then have Chef-solo install a webserver on it</li>
</ul>
 Simple huh? So let's get started.
<!--more-->
## Install needed software
### Install Virtualbox
#### Debian
For this example I'm running [Debian GNU/Linux](http://www.debian.org/) on the **Jessie** (testing) branch. Regardless, we need to have _contrib_ in our <code>/etc/apt/sources.list</code> to get Virtualbox
<pre>deb http://http.debian.net/debian/ jessie main contrib</pre>
 
If you already have a line with _main_ in it, just add _contrib_ to the end
<pre>apt-get update</pre>
 
Now we'll install it, with some headers that it may find useful 
<pre>apt-get install linux-headers-$(uname -r|sed 's,[^-]*-[^-]*-,,') virtualbox</pre>
 
That's it, now by default this setups up Virtualbox to run on boot, which I don't want, so I changed `LOAD_VBOXDRV_MODULE` in `/etc/default/virtualbox` to `0`
<pre>sed -s -i 's/\=1/\=0/' /etc/default/virtualbox</pre>
 
#### Mac OS X
For this example I'm running on my (t)rusty Apple MacBook Pro (5,5 2009) with 4 Gig RAM. All we need to do is visit the [VirtualBox download page](http://www.virtualbox.org/wiki/Downloads) and grab the package for OS X hosts x86/amd64. While we're there we'll also download the VirtualBox 4.2.16 Oracle VM VirtualBox Extension Pack. Once downloaded click on the VirtualBox dmg, install it as normal, then open the VirtualBox Extension Pack, it will automatically open in VirutalBox and install, no big drama there, and we're done. 

The rest of the doc is the same independant of what you're running this on.

### Install Vagrant
 
Now we'll install [vagrant](http://www.vagrantup.com/), now while while your repo __may__ have a version of vagrant to install, __don't install it__! Those are always older than what [rubygems](http://rubygems.org/) provides, plus somewhere down the line it __will__ cause breakage; trust me, [I've done it before](https://fak3r.com/2009/11/18/ruby-on-rails-gem-install-versus-apt-get/). So, first install rubygems
<pre>git clone https://github.com/rubygems/rubygems.git
cd rubygems
ruby setup.rb</pre>
 
Now use rubygems to install it, then the rest of the stuff we need
<pre>gem install vagrant</pre>

### Install Chef

Again, we'll install via gem, so nothing surprising here
<pre>gem install chef</pre>

and then the same to install librarian-chef 
<pre>gem install librarian-chef</pre>

## Configure Vagrant with a base box definition 

Add a box to Vagrant, then tell it about it

<pre>vagrant box add "CentOS 6.4 x86_64 Minimal" http://developer.nrel.gov/downloads/vagrant-boxes/CentOS-6.4-x86_64-v20130427.box
mkdir dev
cd dev
mkdir vagrant 
vagrant init</pre>

Now edit the <code>Vagrant</code> file to tell it about our box's new name. To do so,  change
<pre>config.vm.box = "base"</pre>
to
<pre>config.vm.box = "CentOS 6.4 x86_64 Minimal"</pre>

##Configure Chef by adding cookbook with Librarian-chef
<pre>mkdir chef
cd chef
librarian-chef init</pre>
 
Now edit the <code>Cheffile</code> file to tell it about what we want installed. To do so,  uncomment
<pre>cookbook 'apache2', '>= 1.0.0'</pre>

Now update the cookbooks with Librarian
<pre>librarian-chef update</pre>

Notice the apache2 cookbook has been download and placed in the newly created 'cookbooks' directory

##Tell Vagrant about the new cookbook 
<pre>cd -
vi Vagrantfile</pre>

Tell Vagrant where to find the apache2 cookbook, adding this block near the end
<pre>config.vm.provision :chef_solo do |chef|
	chef.cookbooks_path = "chef/cookbooks"
	chef.add_recipe "apache2"
end</pre>

##Build a vagrant instance, then have Chef-solo install a webserver on it. 

Bring up the box
<pre>vagrant up</pre>

*IF* everything works, you'll see something like the following

<pre>
$ vagrant up
Bringing machine 'default' up with 'virtualbox' provider...
[default] Importing base box 'CentOS 6.4 x86_64 Minimal'...
[default] Matching MAC address for NAT networking...
[default] Setting the name of the VM...
[default] Clearing any previously set forwarded ports...
[default] Fixed port collision for 22 => 2222. Now on port 2202.
[default] Creating shared folders metadata...
[default] Clearing any previously set network interfaces...
[default] Preparing network interfaces based on configuration...
[default] Forwarding ports...
[default] -- 22 => 2202 (adapter 1)
[default] Booting VM...
[default] Waiting for VM to boot. This can take a few minutes.
[default] VM booted and ready for use!
[default] Mounting shared folders...
[default] -- /vagrant
[default] -- /tmp/vagrant-chef-1/chef-solo-1/cookbooks
[default] Running provisioner: chef_solo...
Generating chef JSON and uploading...
Running chef-solo...
[2013-08-30T18:38:56+00:00] INFO: *** Chef 11.4.4 ***
[2013-08-30T18:38:58+00:00] INFO: Setting the run_list to ["recipe[apache2]"] from JSON
[2013-08-30T18:38:58+00:00] INFO: Run List is [recipe[apache2]]
[2013-08-30T18:38:58+00:00] INFO: Run List expands to [apache2]
[2013-08-30T18:38:58+00:00] INFO: Starting Chef Run for localhost
[2013-08-30T18:38:58+00:00] INFO: Running start handlers
[2013-08-30T18:38:58+00:00] INFO: Start handlers complete.
[2013-08-30T18:38:58+00:00] WARN: Cloning resource attributes for service[apache2] from prior resource (CHEF-3694)
[2013-08-30T18:38:58+00:00] WARN: Previous service[apache2]: /tmp/vagrant-chef-1/chef-solo-1/cookbooks/apache2/recipes/default.rb:24:in `from_file'
[2013-08-30T18:38:58+00:00] WARN: Current  service[apache2]: /tmp/vagrant-chef-1/chef-solo-1/cookbooks/apache2/recipes/default.rb:221:in `from_file'
[2013-08-30T18:39:49+00:00] INFO: package[apache2] installing httpd-2.2.15-29.el6.centos from updates repository
[2013-08-30T18:40:10+00:00] INFO: service[apache2] enabled
[2013-08-30T18:40:10+00:00] INFO: directory[/var/log/httpd] mode changed to 755
[2013-08-30T18:40:11+00:00] INFO: cookbook_file[/usr/local/bin/apache2_module_conf_generate.pl] owner changed to 0
[2013-08-30T18:40:11+00:00] INFO: cookbook_file[/usr/local/bin/apache2_module_conf_generate.pl] group changed to 0
[2013-08-30T18:40:11+00:00] INFO: cookbook_file[/usr/local/bin/apache2_module_conf_generate.pl] mode changed to 755
[2013-08-30T18:40:11+00:00] INFO: cookbook_file[/usr/local/bin/apache2_module_conf_generate.pl] created file /usr/local/bin/apache2_module_conf_generate.pl
[2013-08-30T18:40:11+00:00] INFO: directory[/etc/httpd/sites-available] created directory /etc/httpd/sites-available
[2013-08-30T18:40:11+00:00] INFO: directory[/etc/httpd/sites-available] owner changed to 0
[2013-08-30T18:40:11+00:00] INFO: directory[/etc/httpd/sites-available] group changed to 0
[2013-08-30T18:40:11+00:00] INFO: directory[/etc/httpd/sites-available] mode changed to 755
[2013-08-30T18:40:11+00:00] INFO: directory[/etc/httpd/sites-enabled] created directory /etc/httpd/sites-enabled
[2013-08-30T18:40:11+00:00] INFO: directory[/etc/httpd/sites-enabled] owner changed to 0
[2013-08-30T18:40:11+00:00] INFO: directory[/etc/httpd/sites-enabled] group changed to 0
[2013-08-30T18:40:11+00:00] INFO: directory[/etc/httpd/sites-enabled] mode changed to 755
[2013-08-30T18:40:11+00:00] INFO: directory[/etc/httpd/mods-available] created directory /etc/httpd/mods-available
[2013-08-30T18:40:11+00:00] INFO: directory[/etc/httpd/mods-available] owner changed to 0
[2013-08-30T18:40:11+00:00] INFO: directory[/etc/httpd/mods-available] group changed to 0
[2013-08-30T18:40:11+00:00] INFO: directory[/etc/httpd/mods-available] mode changed to 755
[2013-08-30T18:40:11+00:00] INFO: directory[/etc/httpd/mods-enabled] created directory /etc/httpd/mods-enabled
[2013-08-30T18:40:11+00:00] INFO: directory[/etc/httpd/mods-enabled] owner changed to 0
[2013-08-30T18:40:11+00:00] INFO: directory[/etc/httpd/mods-enabled] group changed to 0
[2013-08-30T18:40:11+00:00] INFO: directory[/etc/httpd/mods-enabled] mode changed to 755
[2013-08-30T18:40:11+00:00] INFO: template[/usr/sbin/a2ensite] updated content
[2013-08-30T18:40:11+00:00] INFO: template[/usr/sbin/a2ensite] owner changed to 0
[2013-08-30T18:40:11+00:00] INFO: template[/usr/sbin/a2ensite] group changed to 0
[2013-08-30T18:40:11+00:00] INFO: template[/usr/sbin/a2ensite] mode changed to 700
[2013-08-30T18:40:11+00:00] INFO: template[/usr/sbin/a2dissite] updated content
[2013-08-30T18:40:11+00:00] INFO: template[/usr/sbin/a2dissite] owner changed to 0
[2013-08-30T18:40:11+00:00] INFO: template[/usr/sbin/a2dissite] group changed to 0
[2013-08-30T18:40:11+00:00] INFO: template[/usr/sbin/a2dissite] mode changed to 700
[2013-08-30T18:40:12+00:00] INFO: template[/usr/sbin/a2enmod] updated content
[2013-08-30T18:40:12+00:00] INFO: template[/usr/sbin/a2enmod] owner changed to 0
[2013-08-30T18:40:12+00:00] INFO: template[/usr/sbin/a2enmod] group changed to 0
[2013-08-30T18:40:12+00:00] INFO: template[/usr/sbin/a2enmod] mode changed to 700
[2013-08-30T18:40:12+00:00] INFO: template[/usr/sbin/a2dismod] updated content
[2013-08-30T18:40:12+00:00] INFO: template[/usr/sbin/a2dismod] owner changed to 0
[2013-08-30T18:40:12+00:00] INFO: template[/usr/sbin/a2dismod] group changed to 0
[2013-08-30T18:40:12+00:00] INFO: template[/usr/sbin/a2dismod] mode changed to 700
[2013-08-30T18:40:12+00:00] INFO: file[/etc/httpd/conf.d/welcome.conf] deleted file at /etc/httpd/conf.d/welcome.conf
[2013-08-30T18:40:12+00:00] INFO: file[/etc/httpd/conf.d/README] deleted file at /etc/httpd/conf.d/README
[2013-08-30T18:40:12+00:00] INFO: template[/etc/httpd/mods-available/deflate.conf] updated content
[2013-08-30T18:40:12+00:00] INFO: template[/etc/httpd/mods-available/deflate.conf] mode changed to 644
[2013-08-30T18:40:13+00:00] INFO: entered create
[2013-08-30T18:40:13+00:00] INFO: file[/etc/httpd/mods-available/deflate.load] mode changed to 644
[2013-08-30T18:40:13+00:00] INFO: file[/etc/httpd/mods-available/deflate.load] created file /etc/httpd/mods-available/deflate.load
[2013-08-30T18:40:13+00:00] INFO: execute[a2enmod deflate] ran successfully
[2013-08-30T18:40:13+00:00] INFO: execute[a2enmod deflate] not queuing delayed action restart on service[apache2] (delayed), as it's already been queued
[2013-08-30T18:40:13+00:00] INFO: directory[/etc/httpd/ssl] created directory /etc/httpd/ssl
[2013-08-30T18:40:13+00:00] INFO: directory[/etc/httpd/ssl] owner changed to 0
[2013-08-30T18:40:13+00:00] INFO: directory[/etc/httpd/ssl] group changed to 0
[2013-08-30T18:40:13+00:00] INFO: directory[/etc/httpd/ssl] mode changed to 755
[2013-08-30T18:40:13+00:00] INFO: directory[/var/cache/httpd] created directory /var/cache/httpd
[2013-08-30T18:40:13+00:00] INFO: directory[/var/cache/httpd] owner changed to 0
[2013-08-30T18:40:13+00:00] INFO: directory[/var/cache/httpd] group changed to 0
[2013-08-30T18:40:13+00:00] INFO: directory[/var/cache/httpd] mode changed to 755
[2013-08-30T18:40:13+00:00] INFO: template[/etc/sysconfig/httpd] backed up to /var/chef/backup/etc/sysconfig/httpd.chef-20130830184013
[2013-08-30T18:40:13+00:00] INFO: template[/etc/sysconfig/httpd] updated content
[2013-08-30T18:40:13+00:00] INFO: template[/etc/sysconfig/httpd] owner changed to 0
[2013-08-30T18:40:13+00:00] INFO: template[/etc/sysconfig/httpd] group changed to 0
[2013-08-30T18:40:13+00:00] INFO: template[/etc/sysconfig/httpd] mode changed to 644
[2013-08-30T18:40:13+00:00] INFO: template[/etc/sysconfig/httpd] not queuing delayed action restart on service[apache2] (delayed), as it's already been queued
[2013-08-30T18:40:14+00:00] INFO: template[apache2.conf] backed up to /var/chef/backup/etc/httpd/conf/httpd.conf.chef-20130830184014
[2013-08-30T18:40:14+00:00] INFO: template[apache2.conf] updated content
[2013-08-30T18:40:14+00:00] INFO: template[apache2.conf] owner changed to 0
[2013-08-30T18:40:14+00:00] INFO: template[apache2.conf] group changed to 0
[2013-08-30T18:40:14+00:00] INFO: template[apache2.conf] mode changed to 644
[2013-08-30T18:40:14+00:00] INFO: template[apache2.conf] not queuing delayed action restart on service[apache2] (delayed), as it's already been queued
[2013-08-30T18:40:14+00:00] INFO: template[apache2-conf-security] updated content
[2013-08-30T18:40:14+00:00] INFO: template[apache2-conf-security] owner changed to 0
[2013-08-30T18:40:14+00:00] INFO: template[apache2-conf-security] group changed to 0
[2013-08-30T18:40:14+00:00] INFO: template[apache2-conf-security] mode changed to 644
[2013-08-30T18:40:14+00:00] INFO: template[apache2-conf-security] not queuing delayed action restart on service[apache2] (delayed), as it's already been queued
[2013-08-30T18:40:14+00:00] INFO: template[apache2-conf-charset] updated content
[2013-08-30T18:40:14+00:00] INFO: template[apache2-conf-charset] owner changed to 0
[2013-08-30T18:40:14+00:00] INFO: template[apache2-conf-charset] group changed to 0
[2013-08-30T18:40:14+00:00] INFO: template[apache2-conf-charset] mode changed to 644
[2013-08-30T18:40:14+00:00] INFO: template[apache2-conf-charset] not queuing delayed action restart on service[apache2] (delayed), as it's already been queued
[2013-08-30T18:40:14+00:00] INFO: template[/etc/httpd/ports.conf] updated content
[2013-08-30T18:40:14+00:00] INFO: template[/etc/httpd/ports.conf] owner changed to 0
[2013-08-30T18:40:14+00:00] INFO: template[/etc/httpd/ports.conf] group changed to 0
[2013-08-30T18:40:14+00:00] INFO: template[/etc/httpd/ports.conf] mode changed to 644
[2013-08-30T18:40:14+00:00] INFO: template[/etc/httpd/ports.conf] not queuing delayed action restart on service[apache2] (delayed), as it's already been queued
[2013-08-30T18:40:15+00:00] INFO: template[/etc/httpd/sites-available/default] updated content
[2013-08-30T18:40:15+00:00] INFO: template[/etc/httpd/sites-available/default] owner changed to 0
[2013-08-30T18:40:15+00:00] INFO: template[/etc/httpd/sites-available/default] group changed to 0
[2013-08-30T18:40:15+00:00] INFO: template[/etc/httpd/sites-available/default] mode changed to 644
[2013-08-30T18:40:15+00:00] INFO: template[/etc/httpd/sites-available/default] not queuing delayed action restart on service[apache2] (delayed), as it's already been queued
[2013-08-30T18:40:15+00:00] INFO: template[/etc/httpd/mods-available/status.conf] updated content
[2013-08-30T18:40:15+00:00] INFO: template[/etc/httpd/mods-available/status.conf] mode changed to 644
[2013-08-30T18:40:15+00:00] INFO: template[/etc/httpd/mods-available/status.conf] not queuing delayed action restart on service[apache2] (delayed), as it's already been queued
[2013-08-30T18:40:15+00:00] INFO: entered create
[2013-08-30T18:40:15+00:00] INFO: file[/etc/httpd/mods-available/status.load] mode changed to 644
[2013-08-30T18:40:15+00:00] INFO: file[/etc/httpd/mods-available/status.load] created file /etc/httpd/mods-available/status.load
[2013-08-30T18:40:15+00:00] INFO: execute[a2enmod status] ran successfully
[2013-08-30T18:40:15+00:00] INFO: execute[a2enmod status] not queuing delayed action restart on service[apache2] (delayed), as it's already been queued
[2013-08-30T18:40:16+00:00] INFO: template[/etc/httpd/mods-available/alias.conf] updated content
[2013-08-30T18:40:16+00:00] INFO: template[/etc/httpd/mods-available/alias.conf] mode changed to 644
[2013-08-30T18:40:16+00:00] INFO: template[/etc/httpd/mods-available/alias.conf] not queuing delayed action restart on service[apache2] (delayed), as it's already been queued
[2013-08-30T18:40:16+00:00] INFO: entered create
[2013-08-30T18:40:16+00:00] INFO: file[/etc/httpd/mods-available/alias.load] mode changed to 644
[2013-08-30T18:40:16+00:00] INFO: file[/etc/httpd/mods-available/alias.load] created file /etc/httpd/mods-available/alias.load
[2013-08-30T18:40:16+00:00] INFO: execute[a2enmod alias] ran successfully
[2013-08-30T18:40:16+00:00] INFO: execute[a2enmod alias] not queuing delayed action restart on service[apache2] (delayed), as it's already been queued
[2013-08-30T18:40:16+00:00] INFO: entered create
[2013-08-30T18:40:16+00:00] INFO: file[/etc/httpd/mods-available/auth_basic.load] mode changed to 644
[2013-08-30T18:40:16+00:00] INFO: file[/etc/httpd/mods-available/auth_basic.load] created file /etc/httpd/mods-available/auth_basic.load
[2013-08-30T18:40:16+00:00] INFO: execute[a2enmod auth_basic] ran successfully
[2013-08-30T18:40:16+00:00] INFO: execute[a2enmod auth_basic] not queuing delayed action restart on service[apache2] (delayed), as it's already been queued
[2013-08-30T18:40:17+00:00] INFO: entered create
[2013-08-30T18:40:17+00:00] INFO: file[/etc/httpd/mods-available/authn_file.load] mode changed to 644
[2013-08-30T18:40:17+00:00] INFO: file[/etc/httpd/mods-available/authn_file.load] created file /etc/httpd/mods-available/authn_file.load
[2013-08-30T18:40:17+00:00] INFO: execute[a2enmod authn_file] ran successfully
[2013-08-30T18:40:17+00:00] INFO: execute[a2enmod authn_file] not queuing delayed action restart on service[apache2] (delayed), as it's already been queued
[2013-08-30T18:40:17+00:00] INFO: entered create
[2013-08-30T18:40:17+00:00] INFO: file[/etc/httpd/mods-available/authz_default.load] mode changed to 644
[2013-08-30T18:40:17+00:00] INFO: file[/etc/httpd/mods-available/authz_default.load] created file /etc/httpd/mods-available/authz_default.load
[2013-08-30T18:40:17+00:00] INFO: execute[a2enmod authz_default] ran successfully
[2013-08-30T18:40:17+00:00] INFO: execute[a2enmod authz_default] not queuing delayed action restart on service[apache2] (delayed), as it's already been queued
[2013-08-30T18:40:17+00:00] INFO: entered create
[2013-08-30T18:40:18+00:00] INFO: file[/etc/httpd/mods-available/authz_groupfile.load] mode changed to 644
[2013-08-30T18:40:18+00:00] INFO: file[/etc/httpd/mods-available/authz_groupfile.load] created file /etc/httpd/mods-available/authz_groupfile.load
[2013-08-30T18:40:18+00:00] INFO: execute[a2enmod authz_groupfile] ran successfully
[2013-08-30T18:40:18+00:00] INFO: execute[a2enmod authz_groupfile] not queuing delayed action restart on service[apache2] (delayed), as it's already been queued
[2013-08-30T18:40:18+00:00] INFO: entered create
[2013-08-30T18:40:18+00:00] INFO: file[/etc/httpd/mods-available/authz_host.load] mode changed to 644
[2013-08-30T18:40:18+00:00] INFO: file[/etc/httpd/mods-available/authz_host.load] created file /etc/httpd/mods-available/authz_host.load
[2013-08-30T18:40:18+00:00] INFO: execute[a2enmod authz_host] ran successfully
[2013-08-30T18:40:18+00:00] INFO: execute[a2enmod authz_host] not queuing delayed action restart on service[apache2] (delayed), as it's already been queued
[2013-08-30T18:40:18+00:00] INFO: entered create
[2013-08-30T18:40:18+00:00] INFO: file[/etc/httpd/mods-available/authz_user.load] mode changed to 644
[2013-08-30T18:40:18+00:00] INFO: file[/etc/httpd/mods-available/authz_user.load] created file /etc/httpd/mods-available/authz_user.load
[2013-08-30T18:40:18+00:00] INFO: execute[a2enmod authz_user] ran successfully
[2013-08-30T18:40:18+00:00] INFO: execute[a2enmod authz_user] not queuing delayed action restart on service[apache2] (delayed), as it's already been queued
[2013-08-30T18:40:19+00:00] INFO: template[/etc/httpd/mods-available/autoindex.conf] updated content
[2013-08-30T18:40:19+00:00] INFO: template[/etc/httpd/mods-available/autoindex.conf] mode changed to 644
[2013-08-30T18:40:19+00:00] INFO: template[/etc/httpd/mods-available/autoindex.conf] not queuing delayed action restart on service[apache2] (delayed), as it's already been queued
[2013-08-30T18:40:19+00:00] INFO: entered create
[2013-08-30T18:40:19+00:00] INFO: file[/etc/httpd/mods-available/autoindex.load] mode changed to 644
[2013-08-30T18:40:19+00:00] INFO: file[/etc/httpd/mods-available/autoindex.load] created file /etc/httpd/mods-available/autoindex.load
[2013-08-30T18:40:19+00:00] INFO: execute[a2enmod autoindex] ran successfully
[2013-08-30T18:40:19+00:00] INFO: execute[a2enmod autoindex] not queuing delayed action restart on service[apache2] (delayed), as it's already been queued
[2013-08-30T18:40:19+00:00] INFO: template[/etc/httpd/mods-available/dir.conf] updated content
[2013-08-30T18:40:19+00:00] INFO: template[/etc/httpd/mods-available/dir.conf] mode changed to 644
[2013-08-30T18:40:19+00:00] INFO: template[/etc/httpd/mods-available/dir.conf] not queuing delayed action restart on service[apache2] (delayed), as it's already been queued
[2013-08-30T18:40:20+00:00] INFO: entered create
[2013-08-30T18:40:20+00:00] INFO: file[/etc/httpd/mods-available/dir.load] mode changed to 644
[2013-08-30T18:40:20+00:00] INFO: file[/etc/httpd/mods-available/dir.load] created file /etc/httpd/mods-available/dir.load
[2013-08-30T18:40:20+00:00] INFO: execute[a2enmod dir] ran successfully
[2013-08-30T18:40:20+00:00] INFO: execute[a2enmod dir] not queuing delayed action restart on service[apache2] (delayed), as it's already been queued
[2013-08-30T18:40:20+00:00] INFO: entered create
[2013-08-30T18:40:20+00:00] INFO: file[/etc/httpd/mods-available/env.load] mode changed to 644
[2013-08-30T18:40:20+00:00] INFO: file[/etc/httpd/mods-available/env.load] created file /etc/httpd/mods-available/env.load
[2013-08-30T18:40:20+00:00] INFO: execute[a2enmod env] ran successfully
[2013-08-30T18:40:20+00:00] INFO: execute[a2enmod env] not queuing delayed action restart on service[apache2] (delayed), as it's already been queued
[2013-08-30T18:40:20+00:00] INFO: template[/etc/httpd/mods-available/mime.conf] updated content
[2013-08-30T18:40:20+00:00] INFO: template[/etc/httpd/mods-available/mime.conf] mode changed to 644
[2013-08-30T18:40:20+00:00] INFO: template[/etc/httpd/mods-available/mime.conf] not queuing delayed action restart on service[apache2] (delayed), as it's already been queued
[2013-08-30T18:40:20+00:00] INFO: entered create
[2013-08-30T18:40:21+00:00] INFO: file[/etc/httpd/mods-available/mime.load] mode changed to 644
[2013-08-30T18:40:21+00:00] INFO: file[/etc/httpd/mods-available/mime.load] created file /etc/httpd/mods-available/mime.load
[2013-08-30T18:40:21+00:00] INFO: execute[a2enmod mime] ran successfully
[2013-08-30T18:40:21+00:00] INFO: execute[a2enmod mime] not queuing delayed action restart on service[apache2] (delayed), as it's already been queued
[2013-08-30T18:40:21+00:00] INFO: template[/etc/httpd/mods-available/negotiation.conf] updated content
[2013-08-30T18:40:21+00:00] INFO: template[/etc/httpd/mods-available/negotiation.conf] mode changed to 644
[2013-08-30T18:40:21+00:00] INFO: template[/etc/httpd/mods-available/negotiation.conf] not queuing delayed action restart on service[apache2] (delayed), as it's already been queued
[2013-08-30T18:40:21+00:00] INFO: entered create
[2013-08-30T18:40:21+00:00] INFO: file[/etc/httpd/mods-available/negotiation.load] mode changed to 644
[2013-08-30T18:40:21+00:00] INFO: file[/etc/httpd/mods-available/negotiation.load] created file /etc/httpd/mods-available/negotiation.load
[2013-08-30T18:40:21+00:00] INFO: execute[a2enmod negotiation] ran successfully
[2013-08-30T18:40:21+00:00] INFO: execute[a2enmod negotiation] not queuing delayed action restart on service[apache2] (delayed), as it's already been queued
[2013-08-30T18:40:21+00:00] INFO: template[/etc/httpd/mods-available/setenvif.conf] updated content
[2013-08-30T18:40:22+00:00] INFO: template[/etc/httpd/mods-available/setenvif.conf] mode changed to 644
[2013-08-30T18:40:22+00:00] INFO: template[/etc/httpd/mods-available/setenvif.conf] not queuing delayed action restart on service[apache2] (delayed), as it's already been queued
[2013-08-30T18:40:22+00:00] INFO: entered create
[2013-08-30T18:40:22+00:00] INFO: file[/etc/httpd/mods-available/setenvif.load] mode changed to 644
[2013-08-30T18:40:22+00:00] INFO: file[/etc/httpd/mods-available/setenvif.load] created file /etc/httpd/mods-available/setenvif.load
[2013-08-30T18:40:22+00:00] INFO: execute[a2enmod setenvif] ran successfully
[2013-08-30T18:40:22+00:00] INFO: execute[a2enmod setenvif] not queuing delayed action restart on service[apache2] (delayed), as it's already been queued
[2013-08-30T18:40:22+00:00] INFO: entered create
[2013-08-30T18:40:22+00:00] INFO: file[/etc/httpd/mods-available/log_config.load] mode changed to 644
[2013-08-30T18:40:22+00:00] INFO: file[/etc/httpd/mods-available/log_config.load] created file /etc/httpd/mods-available/log_config.load
[2013-08-30T18:40:22+00:00] INFO: execute[a2enmod log_config] ran successfully
[2013-08-30T18:40:22+00:00] INFO: execute[a2enmod log_config] not queuing delayed action restart on service[apache2] (delayed), as it's already been queued
[2013-08-30T18:40:23+00:00] INFO: entered create
[2013-08-30T18:40:23+00:00] INFO: file[/etc/httpd/mods-available/logio.load] mode changed to 644
[2013-08-30T18:40:23+00:00] INFO: file[/etc/httpd/mods-available/logio.load] created file /etc/httpd/mods-available/logio.load
[2013-08-30T18:40:23+00:00] INFO: execute[a2enmod logio] ran successfully
[2013-08-30T18:40:23+00:00] INFO: execute[a2enmod logio] not queuing delayed action restart on service[apache2] (delayed), as it's already been queued
[2013-08-30T18:40:23+00:00] INFO: service[apache2] started
[2013-08-30T18:40:23+00:00] INFO: template[/etc/httpd/mods-available/deflate.conf] sending restart action to service[apache2] (delayed)
[2013-08-30T18:40:25+00:00] INFO: service[apache2] restarted
[2013-08-30T18:40:25+00:00] INFO: Chef Run complete in 87.228839578 seconds
[2013-08-30T18:40:25+00:00] INFO: Running report handlers
[2013-08-30T18:40:25+00:00] INFO: Report handlers complete
</pre>

Now you can connect to the host and look around
<pre>vagrant ssh</pre>

Then once in the environment, make sure it's the kind of box we wanted, and make sure apache is up and running, listening for requests

<pre>ps -fe | grep httpd 
netstat -plunt | grep 80</pre>

Cool huh? Now wait, what if you wanted to start with nginx and not apache2? Easy, let's make a quick change. First we'll destroy the box we just created
<pre>exit 
vagrant destroy -f</pre>

The -f will just make it do it, and give you the 'are you sure' message. Now edit the Cheffile found in the chef directory
<pre>cd chef
vi Cheffile</pre>

change apache2 to nginx so it will look like
<code>cookbook 'nginx', '>= 1.0.0'</code>

Let's tell librarian to get that cookbook
<pre>$ librarian-chef update
Installing build-essential (1.4.2)
Installing apt (2.1.1)
Installing yum (2.3.2)
Installing runit (1.2.0)
Installing ohai (1.1.12)
Installing nginx (1.8.0)</pre>

Ok, it's installed other cookbooks that it thinks we might need, we won't need apt because we're rocking CentOS, but hey, that's cool. Also notice there is not an apache2 cookbook in the directory anymore, so it's cleaned up what we didn't need, nice!

Now let's tell Vagrantfile about the change
<pre>cd -
vi Vagrantfile</pre>

And just change where it says apache2 to nginx
<code>chef.add_recipe "nginx"</code>

and now kick off a build of that box
<pre>vagrant up </pre>

Do your verification as above, but notice we're not rocking nginx, as it should be. Whew, that was quick, but hey, it's something to build on, and I'll try to demonstrate that going forward by using this same setup to deploy to 'real' virtual machines in the future. Questions/comments, sound off on Twitter, or hit me up via the [contact](/contact) page.

