---
title: "HOWTO: configure MySQL's my.cnf file"
slug: "howto-configure-mysqls-mycnf-fil"
date: "2009-02-18T13:05:09-06:00"
author: "fak3r"
categories:
- geek
- howto
tags:
- configuration
- Database
- my.cnf
- mysql
- RAM
- tuning
---

, I basically went with what it told me, but I'm using a higher query_cache_size than it recommends, basically because I don't see anything online saying it will hurt things.  So I'm now using the following values on my server:

    
    [mysqld]
    user=mysql
    bind-address=127.0.0.1
    datadir=/var/lib/mysql
    pid-file=/var/run/mysqld/mysqld.pid
    socket=/var/run/mysql/mysql.sock
    port=3306
    tmpdir=/tmp
    language=/usr/share/mysql/english
    skip-external-locking
    query_cache_limit=64M
    query_cache_size=32M
    query_cache_type=1
    max_connections=15
    max_user_connections=300
    interactive_timeout=100
    wait_timeout=100
    connect_timeout=10
    thread_stack=128K
    thread_cache_size=128
    myisam-recover=BACKUP
    key_buffer=64M
    join_buffer=1M
    max_allowed_packet=32M
    table_cache=512M
    sort_buffer_size=1M
    read_buffer_size=1M
    read_rnd_buffer_size=768K
    max_connect_errors=10
    thread_concurrency=4
    myisam_sort_buffer_size=32M
    skip-locking
    skip-bdb
    expire_logs_days=10
    max_binlog_size=100M
    server-id=1
    [mysql.server]
    user=mysql
    basedir=/usr
    [safe_mysqld]
    bind-address=127.0.0.1
    err-log=/var/log/mysqld.log
    pid-file=/var/run/mysqld/mysqld.pid
    open_files_limit=8192
    SAFE_MYSQLD_OPTIONS=”–defaults-file=/etc/my.cnf –log-slow-queries=/var/log/slow-queries.log”
    [mysql]
    [isamchk]
    key_buffer=64M
    sort_buffer=64M
    read_buffer=16M
    write_buffer=16M
    [myisamchk]
    key_buffer=64M
    sort_buffer=64M
    read_buffer=16M
    write_buffer=16M
    [mysqlhotcopy]
    interactive-timeout
    max_heap_table_size = 64 M
    tmp_table_size = 64 M
    !includedir /etc/mysql/conf.d/


<!-- more -->I've gone back and forth over the years configuring [MySQL](http://dev.mysql.com/) for optimal performance, and while I know I'm not there, I now have a new baseline to build from.  From a post called [Standard MYSQL my.cnf configuration](http://nakuls77.wordpress.com/2008/09/14/standard-mysql-mycnf-configuration/), you can see all the base information, but also things like:

    
     key_buffer=256M # 64M for 1GB, 128M for 2GB, 256 for 4GB


Which defines the value (256M) but then spells out ideal base values for you to start with if you have more RAM on your system.  This is very helpful, I'm tried to go a step further by combining it with [Debian's](http://debian.org) default my.cnf that comes on 5.0 (lenny) for MySQL 5.  As I'm always open for suggestions for improvements, please comment if you have a different view on these choices, thanks.  Here it is:<!-- more -->

    
    [client]
    socket=/var/run/mysqld/mysqld.sock
    port=3306
    
    [mysqld_safe]
    socket=/var/run/mysqld/mysqld.sock
    nice=0
    
    [mysqld]
    user=mysql
    bind-address=127.0.0.1
    datadir=/var/lib/mysql
    pid-file=/var/run/mysqld/mysqld.pid
    socket=/var/run/mysql/mysql.sock
    port=3306
    tmpdir=/tmp
    language=/usr/share/mysql/english
    skip-external-locking
    query_cache_limit=1M
    query_cache_size=32M
    query_cache_type=1
    max_connections=3000
    max_user_connections=600
    interactive_timeout=100
    wait_timeout=100
    connect_timeout=10
    thread_stack=128K
    thread_cache_size=128
    myisam-recover=BACKUP
    #key_buffer - 64M for 1GB, 128M for 2GB, 256 for 4GB
    key_buffer=64M
    #join_buffer_size - 1M for 1GB, 2M for 2GB, 4M for 4GB
    join_buffer=1M
    max_allowed_packet=32M
    table_cache=1024
    #sort_buffer_size - 1M for 1GB, 2M for 2GB, 4M for 4GB
    sort_buffer_size=1M
    #read_buffer_size - 1M for 1GB, 2M for 2GB, 4M for 4GB
    read_buffer_size=1M
    #read_rnd_buffer_size - 768K for 1GB, 1536K for 2GB, 3072K for 4GB
    read_rnd_buffer_size=768K
    max_connect_errors=10
    thread_concurrency=4
    #myisam_sort_buffer_size - 32M for 1GB, 64M for 2GB, 128 for 4GB
    myisam_sort_buffer_size=32M
    skip-locking
    skip-bdb
    expire_logs_days=10
    max_binlog_size=100M
    server-id=1
    
    [mysql.server]
    user=mysql
    basedir=/usr
    
    [safe_mysqld]
    bind-address=127.0.0.1
    err-log=/var/log/mysqld.log
    pid-file=/var/run/mysqld/mysqld.pid
    open_files_limit=8192
    SAFE_MYSQLD_OPTIONS=”–defaults-file=/etc/my.cnf –log-slow-queries=/var/log/slow-queries.log”
    
    #[mysqldump]
    #quick
    #quote-names
    #max_allowed_packet=16M
    
    [mysql]
    #no-auto-rehash	# faster start of mysql but no tab completition
    
    [isamchk]
    #key_buffer - 64M for 1GB, 128M for 2GB, 256M for 4GB
    key_buffer=64M
    #sort_buffer - 64M for 1GB, 128M for 2GB, 256M for 4GB
    sort_buffer=64M
    #read_buffer - 16M for 1GB, 32M for 2GB, 64M for 4GB
    read_buffer=16M
    #write_buffer - 16M for 1GB, 32M for 2GB, 64M for 4GB
    write_buffer=16M
    
    [myisamchk]
    #key_buffer - 64M for 1GB, 128M for 2GB, 256M for 4GB
    key_buffer=64M
    #sort_buffer - 64M for 1GB, 128M for 2GB, 256M for 4GB
    sort_buffer=64M
    #read_buffer - 16M for 1GB, 32M for 2GB, 64M for 4GB
    read_buffer=16M
    #write_buffer - 16M for 1GB, 32M for 2GB, 64M for 4GB
    write_buffer=16M
    
    [mysqlhotcopy]
    interactive-timeout
    
    !includedir /etc/mysql/conf.d/
