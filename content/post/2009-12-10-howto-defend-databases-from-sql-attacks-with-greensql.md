---
title: "Defend DB from SQL attacks w/GreenSQL"
slug: "howto-defend-databases-from-sql-attacks-with-greensql"
date: "2009-12-10T17:26:16-06:00"
author: "fak3r"
categories:
- commerce
- geek
- linux
tags:
- data retention
- Database
- firewall
- linux
- mysql
- network security
- open source
- opensource
- postgresql
- sql attacks
---

<img src="/2009/logo.png" alt="" border="0" align="right">**UPDATE**: as if to underscore the importance of this tool and approach, yesterday a story hit about a [SQL Injection attack infecting over 132,000](http://it.slashdot.org/story/09/12/10/1334205/SQL-Injection-Attack-Claims-132000) systems in short order.  Net-Security have the [full details on this attack](http://www.net-security.org/secworld.php?id=8604), including how it probes the host via JavaScript to check for known vulnerabilities, how it exploits them, and how it ultimately downloads a back-door trojan to get the game going.  It's really amazing to see how complicated and professional these things have gotten, and just adds to the reasoning that we have to step up to the plate and learn how to better defend against them.<br />

I've been privy to some log dumps showing real, and successful, SQL attacks on some [MSSQL](http://en.wikipedia.org/wiki/Microsoft_SQL_Server) servers before, and they weren't pretty.  Of course a SQL injection attack has little to do with the database (well, as long as it's still SQL based at least (nod to [CouchDB](http://couchdb.apache.org/) and [MongoDB](http://www.mongodb.org/))), and more with the code that calls it, and how that code deals with sanitizing inputs.   For this reason [MySQL](http://www.mysql.com/) is just as vulnerable, after all, bad code is bad code.  While a client of mine opted for a firewall 'module' they had to buy an additional licence for, that set them back many thousands of dollars (annually!) I knew there had to be cheaper/better ways to address this kind of vulnerability.  One way of course is to fix the code, but with legacy sites that no one has touched for years, this may be impractcal (I didn't say this, I only heard it as reasoning from management), and the other idea is to proxy the SQL and 'clean' it before it hits the database.  The advantage of this approach is that it protects against known attacks, as well as unknown attacks, since it limits so much of what an attack is allowed to accomplish when trying to get its' foot in the door.  This approach is what the folks over at [GreenSQL](http://www.greensql.net) have done, and it's very impressive.  They sum things up nice and sweet with, "GreenSQL is an Open Source database firewall used to protect databases from SQL injection attacks. GreenSQL works as a proxy for SQL commands and has built in support for MySQL & PostgreSQL . The logic is based on evaluation of SQL commands using a risk scoring matrix as well as blocking known db administrative commands (DROP, CREATE, etc). GreenSQL is distributed under the GPL license." <!-- more --> A high-level view shows GreenSQL acting as the proxy from the frontend to the database:

<div align="center"><img src="/2009/dia.jpg" alt="Green SQL diagram" border="0"><br /><font size="2">GreenSQL Architecture Diagram</font></div>

This sounds and looks ideal, so with that in mind I installed GreenSQL with the default options on a MySQL server, and setup the included web-based Management Console to kick the tires and see what it does.  To show that it's working I logged into a MySQL database, first directly, on port 3306:

    
    # mysql -h 127.0.0.1 -P 3306 -u dbadmin -p
    Enter password:
    Welcome to the MySQL monitor.  Commands end with ; or \g.
    Your MySQL connection id is 24768
    Type 'help;' or '\h' for help. Type '\c' to clear the buffer.

    mysql> show databases;
    +-------------------------+
    | Database                |
    +-------------------------+
    |<all of my database      |
    | names were listed here> |
    +-------------------------+
    32 rows in set (0.00 sec)
    mysql> quit


And that's what you'd expect, you login as a privledged user and you can see all of your databases, simple.  Now I tried it going through GreenSQL, so essentially a proxy to the [database server](http://www.singlehop.com/databasehosting/), on port 3305.

    
    # mysql -h 127.0.0.1 -P 3305 -u dbadmin -p
    Welcome to the MySQL monitor.  Commands end with ; or \g.
    Your MySQL connection id is 24763
    Type 'help;' or '\h' for help. Type '\c' to clear the buffer.
    mysql> show databases;
    Query OK, 0 rows affected (0.00 sec)
    mysql> quit


Ah, ok, I can see how this would make things more secure!  I then logged into the Management console to see what it had to say about the incident, and it told me what had happened:

    
    Matching queries:
    Query:    show databases
    Time:    2009-12-09 22:02:26
    DB User:    dbadmin
    Risk:    31 blocked
    Reason:    Detected attempt to discover db internal information.
    ID:    1


After this I went into my website's config file, (wp-config.php in Wordpress) and instructed it to connect to port 3305, instead of 3306.  A bit of a note, this wasn't directly documented in my wp-config.php file, so to change the port, you just add a colon and the port number at then end of localhost, or your DB hostname, so it looks like this:

    
    define('DB_HOST', 'localhost<strong>:3305</strong>');


I bring this up because most other configs will include a seperate line for the port, but hey, this works too, it was just a little 'gotcha' that I had to Google.  Now GreenSQL is protecting my websites, including fak3r, and I expect to only expand upon this as I learn more ways to protect my servers from the wilds of the Internet.

**Conclusion**: While some will whine that GreenSQL "only" supports MySQL and Postgresql, when I first looked into this they were only supporting MySQL - and that was last month, so I suspect they'll cover things like MSSQL and even Oracle in the future. This would be huge for businesses and corportations of all sizes to protect their data, since sites like Comparitech's [Biggest Data Breaches in History](https://www.comparitech.com/blog/information-security/biggest-data-breaches-in-history/) and Privacy Rights' [Chronology of Data Breaches since 2005](https://www.privacyrights.org/data-breaches) show us that basic database proctions are often STILL NOT IN PLACE. Plus, coming from a firewall background, this seems like an obvious way to protect things. While there's a slight performance trade off, their tests show it to be very minimal, but I'll try to run my own tests and report back. So, make no doubt about it, the GreenSQL folks have put together an enterprise ready product that they are actively developing to address the latest database threats. Highly recommended.

Please post questions or comments below, I'm always learning and and chances are you know something I don't!
