---
title: "Build a log server with Splunk-like features"
date: "2013-08-22T13:03:00-06:00"
categories: 
- linux
- geek
- howto
---
Logging is something that continues to become more and more important, and it used to take great pains to have a centralized log server that everyone could use. Yep, I used [Splunk](http://www.splunk.com/) early on, when they had a good open source option, but now their 'freemium' only offering leaves a gap. So let's create one using open source software and get some of the Splunk-like features by building a basic log server. Now some will say, you should do it this way, you should scale it this way, etc, but my goal here is to have something that works, something admins can use, and then once they 'get it', they can expand it, update it as they need. So for this we'll be using [Logstash](http://logstash.net) as the log parser, which will recieve and send the logs to [Elasticsearch](http://www.elasticsearch.org/), which is the backend that stores the logs while allowing searching against them, which will be viewed by [Kibana](http://kibana.org/), a web/front end. Let's see how simply we can do this.
<!--more-->

I'm running <a href="http://debian.org">Debian GNU/Linux</a> (testing, Jessie) for this example, so most of these commands will drop right into Ubuntu, but will require a few tweaks to paths and such for RHEL and CentOS, but it's not far off.

<h3>Starting</h3>
Create a directory to hold everything, and change to it
 
```
mkdir /opt/logserver
cd /opt/logserver
```

<h3>Elasticsearch</h3>
Install and start Elasticsearch

```
mkdir /opt/logserver/elasticsearch; cd /opt/logserver/elasticsearch
wget http://download.elasticsearch.org/elasticsearch/elasticsearch/elasticsearch-0.90.3.tar.gz
tar zxvf elasticsearch-0.90.3.tar.gz
cd elasticsearch-0.90.3
./bin/elasticsearch -f
```

<h3>Kibana</h3>
Install, configure and start Kibana

```
mkdir /opt/logserver/kibana; cd /opt/logserver/kibana
wget http://github.com/rashidkpc/Kibana/archive/kibana-ruby.tar.gz
tar zxvf kibana-ruby.tar.gz
cd Kibana-kibana-ruby/
```

Now we need <code>bundle</code> to install and run Kibana, if you don't have it you'll need to have Ruby installed and it's easiest to install this via [RVM](https://rvm.io/rvm/install). To install RVM, Ruby (1.9.3) and <code>bundle</code> all at once, you just give RVM some arguments

```
curl -L https://get.rvm.io | bash --ruby=1.9.3 --gems=bundle
```

Now we can install the requirements via <code>bundler</code> 

```
bundle install --path vendor/bundle
```

If all goes well, you'll be rewarded with a message that says, <code>Your bundle is complete!</code>

Now you can setup your Kibana server by editing <code>KibanaConfig.rb</code> since this is on the same host, lets set it to listen on all interfaces so it can be hit via the
browser easily

```
KibanaHost = '0.0.0.0'
```

Then startup Kibana

```
bundle exec ruby kibana.rb
```

You should be able to hit it via <code>IP_OF_YOUR_HOST:5601</code>. So, did it work? Cool, there are no logs there yet, but hey, it's ready for them, so let's configure some to be sent in.

<h3>Logstash</h3>
Install, configure and run Logstash in standalone mode.

```
mkdir /opt/logserver/logstash; cd /opt/logserver/logstash
wget http://logstash.objects.dreamhost.com/release/logstash-1.1.13-flatjar.jar
```

now edit logstash.conf, and add the following

```
input {
  stdin {
    type => "stdin-type"
  }
 
  file {
    type => "syslog"
 
    # Wildcards work, here :)
    path => [ "/var/log/*.log", "/var/log/messages", "/var/log/syslog" ]
  }
 
  file {
    type => "apache"
    path => [ "/var/log/httpd/*.log", "/var/log/nginx/*.log" ]
  }

filter {
  grok {
	type => "apache"
        # See the following URL for a complete list of named patterns
        # logstash/grok ships with by default:
        # https://github.com/logstash/logstash/tree/master/patterns
        #
        # The grok filter will use the below pattern and on successful match use
        # any captured values as new fields in the event.
               pattern => "%{COMBINEDAPACHELOG}"
        }
 
  date {
        type => "apache"
        # Try to pull the timestamp from the 'timestamp' field (parsed above with
        # grok). The apache time format looks like: "18/Aug/2011:05:44:34 -0700"
        locale => en
        match => ["timestamp", "dd/MMM/yyyy:HH:mm:ss Z"]
        }
}
 
output {
  stdout { }
 
  elasticsearch {
        host => "127.0.0.1"
  }
}
```

Pretty simple to figure things out, for now everything is on the same host, you'll notice the 'web' log block that will read web logs from nginx or Apache logs automatically, whatever it finds, it'll use. Also, anything it defines as Apache will go through that filter to have patterns recognized and the date string formated like syslog. For rsyslog logs, we can use <code>/var/log/messages</code> (which is what CentOS/RHEL use) and <code>/var/log/syslog</code> (which Debian/Ubuntu uses) declarations like we did with Apache, and it covers both of those - making it easy to reuse this config.

Now let's start logstash with this new config

```
java -jar logstash-1.1.13-flatjar.jar agent -f logstash.conf
```

And now navigate to your Kibana web view again, wait, or force some events to log by sshing to box, hitting
web pages, etc, to see how things show up in Kibana. The searching/filtering takes a little bit of time to
understand (or did for me) but it's pretty slick once you get it. There's an option to 'stream' the logs via
the console, so you can almost 'tail' all the logs in your browser.
 
So for our last step, we'll use rsyslog on a remote host to send syslog, Apache, Mysql logs on to our little
logserver. Like before, Logstash will catch it, put it in elasticsearch and then it'll show up in Kibana.
We have two steps, the first will require root access on a remote host. First, on the remote
host, create a new rsyslog config file that loads the logfiles via rsyslog's imfile plugin and then ships them to the
logserver. Create a new file called <code>/etc/rsyslog.d/logserver.conf</code> with the following

```
# From http://cookbook.logstash.net/recipes/rsyslog-agent/rsyslog.conf
$ModLoad imfile   # Load the imfile input module
$ModLoad imklog   # for reading kernel log messages
$ModLoad imuxsock # for reading local syslog messages

# Watch /var/log/apache2/access.log
$InputFileName /var/log/apache2/access.log
$InputFileTag apache-access:
$InputFileStateFile state-apache-access
$InputRunFileMonitor

# Watch /var/log/apache2/error.log
$InputFileName /var/log/apache2/error.log
$InputFileTag apache-error:
$InputFileStateFile state-apache-error
$InputRunFileMonitor

# Watch /var/log/mysql/mysql.log
$InputFileName /var/log/mysql/mysql.log
$InputFileTag mysql:
$InputFileStateFile state-mysql
$InputRunFileMonitor

# Send everything to a logstash server named 'myserver' on port 5544:
#*.* @@myserver:5544
*.* @@${IP_OR_HOSTNAME}:5544
```

Of course, adjust any log file locations (or add them), and be sure to replace ${IP_OR_HOSTNAME} with the name or IP of your logserver host, and be sure to keep the double @ in there (ie- <code>@@123.4.5.6:5544</code>)

Then restart rsyslog so it will use the new config

```
/etc/init.d/rsyslog restart
```

Back on the logserver, we need to tell our logstash server to listen on :5544 for logs, but we're also going to rework how it parses syslog events, since there will be more in these than just syslog. There's a new filter block to cover that, but again at the end we'll still use the same output, elasticsearch.

```
input {
  stdin {
    type => "stdin-type"
  }

  file {
    type => "syslog"

    # Wildcards work, here :)
    path => [ "/var/log/*.log", "/var/log/messages", "/var/log/syslog" ]
  }

  file {
    type => "web"
    path => [ "/var/log/httpd/*.log", "/var/log/nginx/*.log", "/var/log/apache2/*.log" ]
  }

  tcp {
    port => 5544
    type => syslog
  }
  udp {
    port => 5544
    type => syslog
  }
}

filter {
  grok {
      type => "syslog"
      pattern => [ "<%{POSINT:syslog_pri}>%{SYSLOGTIMESTAMP:syslog_timestamp} %{SYSLOGHOST:syslog_hostname} %{DATA:syslog_program}(?:\[%{POSINT:syslog_pid}\])?: %{GREEDYDATA:syslog_message}" ]
      add_field => [ "received_at", "%{@timestamp}" ]
      add_field => [ "received_from", "%{@source_host}" ]
  }
  syslog_pri {
      type => "syslog"
  }
  date {
      type => "syslog"
      match => [ "syslog_timestamp", "MMM  d HH:mm:ss", "MMM dd HH:mm:ss" ]
  }
  mutate {
      type => "syslog"
      exclude_tags => "_grokparsefailure"
      replace => [ "@source_host", "%{syslog_hostname}" ]
      replace => [ "@message", "%{syslog_message}" ]
  }
  mutate {
      type => "syslog"
      remove => [ "syslog_hostname", "syslog_message", "syslog_timestamp" ]
  }
}

filter {
  grok {
	type => "apache"
        # See the following URL for a complete list of named patterns
        # logstash/grok ships with by default:
        # https://github.com/logstash/logstash/tree/master/patterns
        #
        # The grok filter will use the below pattern and on successful match use
        # any captured values as new fields in the event.
               pattern => "%{COMBINEDAPACHELOG}"
        }
 
  date {
        type => "apache"
        # Try to pull the timestamp from the 'timestamp' field (parsed above with
        # grok). The apache time format looks like: "18/Aug/2011:05:44:34 -0700"
        locale => en
        match => ["timestamp", "dd/MMM/yyyy:HH:mm:ss Z"]
        }
}

output {
  stdout { }

  elasticsearch {
        host => "127.0.0.1"
  }
}
```

And then you just need to restart logstash, as we did above, it will be the same as before, but now it will listen for logs on :554

```
java -jar logstash-1.1.13-flatjar.jar agent -f logstash.conf
```

A quick netstat will tell you that it's listening on the right port (UDP and TCP since syslog will use UDP)

```
# netstat -plunt|grep 5544
tcp6       0      0 :::5544                 :::*                    LISTEN      16368/java
udp6       0      0 :::5544                 :::*                                16368/java
```

<h3>Finished</h3>
And now you can view things via Kibana in your browser, give it a few minutes to get rolling, then use the 'search' field to cut down on the noise, maybe filtering by hostname, then click 'stream' and you'll have a tail like output from that host in your browser. How did it work, anything I missed? Sound off via Twitter or hit me up via my contact page if you have/need any feedback and I'll update this post as necessary.

Now since I mentioned it, I should tell you a UDP joke, but you may not get it. -- No, that's not mine, but I've heard it online and it's a good one. 
