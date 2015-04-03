---
title: "HOWTO: install Fedora-commons repository software on Debian"
slug: "howto-install-fedora-commons-repository-software-on-debian"
date: "2009-03-17T20:25:57-06:00"
author: "fak3r"
categories:
- geek
- howto
tags:
- Debian GNU/Linux
- fedora-commons
- fedoracommons
- guide
- installation
- Java
- MySQL store
---

![newlogo2](http://www.fak3r.com/wp-content/uploads/2009/03/newlogo2.jpg)So I've been using [Fedora-commons](http://www.fedora-commons.org/) for almost a year, first off, no it is NOT the [Linux distribution](http://fedoraproject.org/), it is a digital repository used by libraries, museums, etc, worldwide to keep track of their digital collections.  For this Fedora-commons is very good at its job, but there was a steep learning curve when I first jumped in with, a complaint I've heard repeated by many who aren't Java jockeys (just made that up).  Today I had to reinstall Fedora-commons on a new development server, and to be honest I had a couple of missteps along the way as I tried to remember my super cool moves to install this app.  Once something like this happens it's a prime candidate for a HOWTO, so here is my super, simple HOWTO get Feodora-commons up and running in a development environment in Debian GNU/Linux (I'm running Lenny - 5.0) or Ubuntu Linux.  Notice that for simplicity's sake, this uses Fedora-commons built in Tomcat implementation, for developing this is fine, for production I highly recommend installing a Tomcat via dpkg/apt-get, or whatever package manager you use, so that any security patches will be available for apt-get to automatically update.  I've also set it to use a local [MySQL](http://www.mysql.com/) store, change to a different database if you need to.<!-- more -->



	
  1. [download the latest version](http://www.fedora-commons.org/software) of Fedora-commons (my example uses wget, since that's how I roll, and is with the latest currently available version, modify as needed)


    
    wget http://downloads.sourceforge.net/fedora-commons/fcrepo-installer-3.3.jar


	
  2. create a filed called  'install30.properties' so you don't have to answer all the questions the installer asks.  Read through this example and modify as needed.


    
    # example install.properties
    ri.enabled=true
    messaging.enabled=true
    apia.auth.required=false
    database.jdbcDriverClass=com.mysql.jdbc.Driver
    ssl.available=false
    database.jdbcURL=jdbc\:mysql\://localhost/fedora33?useUnicode\=true&amp;characterEncoding\=UTF-8&amp;autoReconnect\=true
    messaging.uri=vm\:(broker\:(tcp\://localhost\:61616))
    database.password=secret
    database.mysql.driver=included
    database.username=root
    tomcat.shutdown.port=8001
    deploy.local.services=true
    xacml.enabled=false
    database.mysql.jdbcDriverClass=com.mysql.jdbc.Driver
    tomcat.http.port=8080
    fedora.serverHost=localhost
    database=mysql
    database.driver=included
    tomcat.home=/opt/fedora/tomcat
    fedora.home=/opt/fedora
    rest.enabled=true
    install.type=custom
    servlet.engine=included
    fedora.admin.pass=fedoraAdmin


	
  3. create a database as defined above (NOTE: this could be different for you, if you're not using a DB, enter database.jdbcDriverClass=com.mcoi.jdbc.Driver and remove the MySQL specfic lines)


    
    mysqladmin -h localhost -u root -p  create fedora33


	
  4. then run the Fedora-commons installer, using the -jar switch with Java and calling out your properties file at the end


    
    /usr/lib/jvm/java-6-sun/jre/bin/java -jar fcrepo-installer-3.3.jar install.33.properties




	
  1. to start Fedora-commons, define your FEDORA_HOME, JAVA_HOME, etc in your profile, or a simple BASH start script - here's an example, again modify as needed.


    
    # example start_fedora.sh
    export JAVA_HOME="/usr/lib/jvm/java-6-sun"
    export FEDORA_HOME="/opt/fedora"
    export CATALINA_HOME="$FEDORA_HOME/tomcat"
    /opt/fedora/tomcat/bin/startup.sh


	
  2. run the BASH script, watch for any errors


    
    ./start-fedora.sh


	
  3. view the Fedora Admin within a web browser (change the URL to suit)


    
    http://localhost.domain.com:8080/fedora/search


	
  4. later, learn how to [import stuff](https://wiki.duraspace.org/display/FCR30/Getting+Started+with+Fedora)

	
  5. ???

	
  6. Profit!


Thanks goes out to all at Fedora-commons, specifically those on the mailing list that walked me through the early steps and made that learning curve just a bit flatter.  I'm still planning on working on a Debian 'deb' installer to simplify the above process.  If you hit any questions or have issues with the above, leave a message below.

Thanks
