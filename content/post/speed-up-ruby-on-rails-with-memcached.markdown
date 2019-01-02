---
title: "Speed up Ruby-on-Rails with memcached"
slug: "speed-up-ruby-on-rails-with-memcached"
date: "2006-05-11T18:46:00-06:00"
author: "fak3r"
categories:
- General
tags:
- bsd
- hacker
- howto
---

I wanted Memcached to speed up my CMS Typo, by allowing Ruby to take advantage of it for higher performance. Below are steps compiled from both sites, and used on my FreeBSD 6.0 server, but most of the steps should work as well in Linux. Read more for the steps.

<!--more-->

First let’s get memcached installed

```
cd /usr/ports/databases/memcached/
```

We only want it to get past the configure step before we modify code

```
make configure
```

Now it’s time to modify the code (NOTE: the howto linked to above was specific to a FreeBSD issue, if using Linux you may not need to make this modification)
    
```
vi work/memcached-1.1.12/memcached.c
```

Find this line

```
#include "memcached.h"
```

Add the undef line below it and save

```
#include "memcached.h"
#undef TCP_NOPUSH
```

Now we want it to compile and install

```
make install
```

Once that’s complete, we want to enable memcached in rc.conf

```
echo "memcached_enable="YES"" >> /etc/rc.conf
```

Then start memcached
    
```
/usr/local/etc/rc.d/memcached.sh start
```

Next we’ll install the ruby-memcache client

```
cd ../ruby-memcache/
make install
```

Finally we’ll modify our Ruby-on-Rails app’s environment to use memcache as its session store (make a backup first!)

```
cp config/environment.rb config/environment.rb.dist
vi config/environment.rb
```

Find the line that tells session_store to use the database instead of the file system

```
#config.action_controller.session_store = :active_record_store
```

Modify it so it tells it to use memcached, and save

```
config.action_controller.session_store = :mem_cache_store
```

Stop Typo, and then manually clear the cache

```
rake sweep_cache
```

Now restart your Typo server, and you’re done! It should now be storing all session data via memcached instead of your database.
