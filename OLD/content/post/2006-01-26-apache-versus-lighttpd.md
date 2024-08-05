---
title: "Apache versus Lighttpd"
slug: "apache-versus-lighttpd"
date: "2006-01-26T22:04:00-06:00"
author: "fak3r"
categories:
- geek
tags:
- newstudy
---

Since I’ve been running Typo for this blog I’ve been having Apache do a mod_proxy to pass anything bound for fak3r.com to a port that Typo is running on with [LightTPD](http://www.lighttpd.net/) spitting up the pages.  I’ve read about how much faster Lighttpd is, but today I wanted to test it out on my own enviroment in the hopes that it would convince me to migrate over, if for nothing else that to learn a new webserver.  Well, you can draw your own conclusions from the results, and I’ll update things later.  First we run the [ApacheBench](http://httpd.apache.org/docs/1.3/programs/ab.html) (packaged with Apache, or available via CPAN - HTTPD::Bench::ApacheBench) benchmarking tool set for 10 concurrent users, each making 1000 requests (so a total of 10,000 requests) with [Apache](http://www.apache.org) 2.0.55 running.  Note that is simply pulling whatever it finds in /:




    
    <code>[13:50:01] [root@pepe ~]# ab -n 10000 -c 10 http://localhost/
    This is ApacheBench, Version 2.0.41-dev <$Revision: 1.121.2.12 $> apache-2.0
    Copyright (c) 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
    Copyright (c) 1998-2002 The Apache Software Foundation, http://www.apache.org/
    
    Benchmarking localhost (be patient)
    Completed 1000 requests
    Completed 2000 requests
    Completed 3000 requests
    Completed 4000 requests
    Completed 5000 requests
    Completed 6000 requests
    Completed 7000 requests
    Completed 8000 requests
    Completed 9000 requests
    Finished 10000 requests
    
    Server Software:        Apache
    Server Hostname:        localhost
    Server Port:            80
    
    Document Path:          /
    Document Length:        1494 bytes
    
    Concurrency Level:      10
    Time taken for tests:   17.28184 seconds
    Complete requests:      10000
    Failed requests:        0
    Write errors:           0
    Total transferred:      17280000 bytes
    HTML transferred:       14940000 bytes
    Requests per second:    587.26 [#/sec] (mean)
    Time per request:       17.028 [ms] (mean)
    Time per request:       1.703 [ms] (mean, across all concurrent requests)
    Transfer rate:          991.00 [Kbytes/sec] received
    
    Connection Times (ms)
    min  mean[+/-sd] median   max
    Connect:        0    0   0.9      0      84
    Processing:     2   15   3.2     16     102
    Waiting:        0    8   5.3     10     100
    Total:          2   15   3.3     16     102
    
    Percentage of the requests served within a certain time (ms)
    50%     16
    66%     16
    75%     17
    80%     17
    90%     17
    95%     17
    98%     18
    99%     19
    100%    102 (longest request)</code>





And now the same test, 10,000 requests total, but with [LightTPD](http://www.lighttpd.net/) 1.4.9 running the show:




    
    <code>[14:07:16] [root@pepe /usr/local/etc]# ab -n 10000 -c 10 http://localhost/
    This is ApacheBench, Version 2.0.41-dev <$Revision: 1.121.2.12 $> apache-2.0
    Copyright (c) 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
    Copyright (c) 1998-2002 The Apache Software Foundation, http://www.apache.org/
    
    Benchmarking localhost (be patient)
    Completed 1000 requests
    Completed 2000 requests
    Completed 3000 requests
    Completed 4000 requests
    Completed 5000 requests
    Completed 6000 requests
    Completed 7000 requests
    Completed 8000 requests
    Completed 9000 requests
    Finished 10000 requests
    
    Server Software:        lighttpd/1.4.9
    Server Hostname:        localhost
    Server Port:            80
    
    Document Path:          /
    Document Length:        1494 bytes
    
    Concurrency Level:      10
    Time taken for tests:   6.337037 seconds
    Complete requests:      10000
    Failed requests:        0
    Write errors:           0
    Total transferred:      17270000 bytes
    HTML transferred:       14940000 bytes
    Requests per second:    1578.02 [#/sec] (mean)
    Time per request:       6.337 [ms] (mean)
    Time per request:       0.634 [ms] (mean, across all concurrent requests)
    Transfer rate:          2661.34 [Kbytes/sec] received
    
    Connection Times (ms)
    min  mean[+/-sd] median   max
    Connect:        0    0   0.0      0       1
    Processing:     4    5   1.0      6      22
    Waiting:        0    5   1.0      5      22
    Total:          4    5   1.0      6      22
    
    Percentage of the requests served within a certain time (ms)
    50%      6
    66%      6
    75%      6
    80%      6
    90%      6
    95%      6
    98%      7
    99%      7
    100%     22 (longest request)</code>





So yes, this just shows Lighttpd kicking ass on pulling a small (1.5K) file over and over, but shows that they have a different approach.  I’m going to do some more benchmarking, but want to move my entire webserver over to Lighttpd, and will once I figure out how to fix the [Drupal 4.6 not working with lighttpd 1.4, php 4.4](http://drupal.org/node/29438).  Essentially it fails to find any of the multi hosted ‘sites’ hosted under the same Drupal instance.  I currently have 3, but it defaults to ‘default’ making it unable (or unwilling?) to display the other sites.  My options are try to fix it, or migrate all the sites over to Typo; both are being considered.
