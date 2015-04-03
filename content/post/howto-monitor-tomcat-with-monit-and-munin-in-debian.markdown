---
title: "HOWTO monitor Tomcat with monit and munin in Debian"
slug: "howto-monitor-tomcat-with-monit-and-munin-in-debian"
date: "2010-10-07T18:45:58-06:00"
author: "fak3r"
categories:
- geek
- howto
tags:
- debian
- Java
- java heap
- linux
- monit
- monitoring
- munin
- server
- tomcat
- ubuntu
---

[![](http://fak3r.com/wp-content/uploads/2010/10/apache-tomcat.jpg)](http://fak3r.com/wp-content/uploads/2010/10/apache-tomcat.jpg)I have an existing [Tomcat](http://tomcat.apache.org/) installation in production that has been running hot and causing monit to send me notices that such and such service is down, only to come back clear on the next run. Of course since I use [monit](http://mmonit.com/monit/) I can see that the service was never restarted, plus I've never had this happen on other servers with monit, so I'm convinced that Tomcat, with its hunger for Java, is the culrprit here. I've been running [munin](http://munin-monitoring.org/) for years on this server too, however I never got the Tomcat plugins to work with it, so I can't gauge how hot Tomcat is running, and how changing the heap size is effecting things. Because of this, yesterday I got serious about it and finally got it working, but I had to take an end run to get it rolling and it wasn't fun; which is why I'm posting it here. If anyone knows a better way to do this, please share in the comments and I'll update this, but here's how I was successful.

<!-- more -->

First off, this server is running [Debian GNU/Linux](http://www.debian.org/) 'Lenny', and it's been in service for a few years. It has only rebooted when the hosting facility looses power, which took over 220 days last time, so it's a stable server to say the least. To start you want to install Tomcat, notice that since I'm running Lenny, it still uses Tomcat5.5. Installing is easy, but be sure to include the webapp package

    
    <span><span><span><span><span><span><span><span><span style="font-family: Consolas, Monaco, 'Courier New', Courier, monospace; line-height: 18px; font-size: 12px;">apt-get install tomcat5.5 tomcat5.5-webapp</span></span></span></span></span></span></span></span></span>


[![](http://fak3r.com/wp-content/uploads/2010/04/monit_banner.png)](http://fak3r.com/wp-content/uploads/2010/04/monit_banner.png)I won't spend too much time on monit, since I've [covered it extensively elsewhere on my site](http://fak3r.com/2010/04/10/howto-use-monit-to-monitor-sites-and-alert-users/) (if you haven't, go there to understand how to set it up, and why I don't run a system without it), but basically to have it watch Tomcat we need to install it

apt-get install monit

And then tell it to watch Tomcat with a config like this

    
    check process tomcat with pidfile /var/run/tomcat5.5.pid
    <span>	start program = "/etc/init.d/tomcat5.5 start"</span>
    <span>	stop program  = "/etc/init.d/tomcat5.5 stop"</span>
    <span>	if cpu > 60% for 2 cycles then alert</span>
    <span>        if cpu > 80% for 5 cycles then restart</span>
    <span>        if totalmem > 1500.0 MB for 5 cycles then restart</span>
    <span>	if failed host 127.0.0.1 port 113 then alert</span>
    <span>	if failed host 127.0.0.1 port 8180 then alert</span>
    <span>	if 5 restarts within 5 cycles then timeout</span>


[![](http://fak3r.com/wp-content/uploads/2010/10/munin.png)](http://fak3r.com/wp-content/uploads/2010/10/munin.png)Easy cheesy, so next we'll look at munin. Munin is described as a networked resource monitoring tool that helps analyze resource trends and problems or hickups on your setup. It's pretty simple to get up and running and proves quite a few graphs right off the bat, so let's install  munin and munin-node

    
    apt-get install munin munin-node munin-plugins-extra


To ensure the basic monitors are working either wait for a few cron runs for munin to start populating data automatically, or force it by running it as user 'munin' (you can also run this as root with 'munin-cron --force-root')

    
    su - munin
    munin-cron


Then check [http://yoursite.com/munin](http://yoursite.com/munin) to see if your graphs are populating, they should be.

Now let's add a username and password in the munin plugins for Tomcat, password can, and should, obviously be different that the username.

    
    vi /etc/munin/plugins/tomcat_*
         my $USER     = exists $ENV{'user'}     ? $ENV{'user'}     : "munin";
         my $PASSWORD = exists $ENV{'password'} ? $ENV{'password'} : "munin";


Also within this file you'll see the line that tells us that we need the manager webapp to be running on Tomcat for munin to be able to find out what Tomcat is up to

    
    my $URL      = exists $ENV{'url'}      ? $ENV{'url'}      :\
        "http://%s:%s\@127.0.0.1:%d/manager/status?XML=true";


We'll deal with this later, for now we can enable the Tomcat plugins by sym-linking them from their /usr/share home

    
    ln -s /usr/share/munin/plugins/tomcat_access /etc/munin/plugins/tomcat_access
    ln -s /usr/share/munin/plugins/tomcat_jvm /etc/munin/plugins/tomcat_jvm
    ln -s /usr/share/munin/plugins/tomcat_threads /etc/munin/plugins/tomcat_threads
    ln -s /usr/share/munin/plugins/tomcat_volume /etc/munin/plugins/tomcat_volume


We don't want to restart munin yet because we don't have Tomcat configured, to do this first we add the munin user and password  (this can be anything, just needs to match what you put in /etc/munin/plugins/tomcat_*) to Tomcat's users file

    
    vi /var/lib/tomcat5.5/conf/tomcat-users.xml
        + <user username="munin" password="munin" roles="manager,standard"/>




BAM! And this is where I hit the wall. After all this setup and configuration I hit a snag on installing and enabling the Tomcat Manager webapp. Even though I installed tomcat5.5-webapp and tomcat5.5-admin, I could not find the manager, and there is no corresponding tomcat5.5-manager package available in Lenny! While I found plenty of good [documentation online](http://tomcat.apache.org/tomcat-5.5-doc/manager-howto.html) for how to use the manager webapp, nothing about how to get it if you don't have it. Apparently in Debian it used to be bundled in with Tomcat, but was broken up into separate packages; Tomcat and Tomcat Admin - but as far as I can tell, there is no apt-get installable Tomcat Manager (someone can prove me wrong now, it's fine) in Lenny. So eventually I figured I'd just grab a tarball of the latest 5.5 Tomcat (which ships Tomcat along with the Admin and Manager, just disabled by default), and take the manager out of that and install it in my running Tomcat. There were a few extra manual steps though, so first to install the manager webapp in Tomcat, download the full tarball (about 7.9Meg), extract it and change into the new directory it creates



    
    wget http://apache.org/dist/tomcat/tomcat-5/v5.5.31/bin/apache-tomcat-5.5.31.tar.gz
    tar -zxf apache-tomcat-5.5.31.tar.gz
    cd apache-tomcat-5.5.31


Next we need to modify the existing manager.xml so it uses our default Debian Tomcat path, and then we'll also add a line to only allow access to the manager webapp by localhost (127.0.0.1) to prevent anyone from taking control of Tomcat (a little easter egg you could look for in publicly available Tomcat installations :))

    
    vi conf/Catalina/localhost/manager.xml
        - <Context docBase="${catalina.home}/server/webapps/manager"
        + <Context docBase="/var/lib/tomcat5.5/webapps/manager"
        + <Valve className="org.apache.catalina.valves.RemoteAddrValve" allow="127.0.0.1"/>


Then put it in place under Catalina home, and set the proper permissions on the file.

    
    cp conf/Catalina/localhost/manager.xml /var/lib/tomcat5.5/Catalina/localhost
    chown tomcat55:adm /var/lib/tomcat5.5/Catalina/localhost/manager.xml


Now we have a similar step to actually install the manager webapp

    
    cp -R server/webapps/manager /var/lib/tomcat5.5/webapps
    chown -R tomcat55:nogroup /var/lib/tomcat5.5/webapps/manager


And that's it, we should be good to go now, so let's restart munin and Tomcat to get the game going.

    
    /etc/init.d/munin restart
    /etc/init.d/tomcat5.5 restart


Next watch your /munin directory in your browser and watch for the Tomcat based graphs to start updating. Did it work? If not don't worry, I've been a long time admin known to curse Tomcat and Java for it's errors - ask away below and we'll see what we can do to get things rolling.
