+++
draft = false
tags = ["howto", "commandline", "rpm", "yum", "linux" ]
topics = [ "sysadmin" ]
title = "Checking and fixing broken RPM packages"
description = "Recovering from a disaster when you thought all hope was lost"
date = "2017-10-23T14:44:11-06:00"

+++
Sometimes you'll run something in the commandline, only to realise you weren't in the directory you thought you were in. Most of the time this is fine, you get an error and move on. But wait, what if you think you're moving backups to another directory and you just use a `*` to catch them all because you're not paying attention... oh, and it ends up you were in `/` at the time. That's not good, you've just destroyed a working system since this is outside of a normal mistake. But after the shock I started poking around online to see if I could fix it - and if you can't tell from this post, I did fix it! First of all I carefully moved `/bin`, `/boot` and even `/dev` back in place - but to do that I had to find `mv`, which you'll recall, I moved (by accident!) So I pathed to the new directory where `mv` was living, used that to move things back in place, so then I had most of my commands back, not bad. Later, finding strange issues I couldn't figure out, I thought I'd reinstall the installed packages, but which ones were broken? This was Red Hat Enterprise Linux (RHEL), not my first choice, but what the client I'm working for is using, so we're dealing with RPM packages. I found the command to give me a list of broeken packages, so I ran that:

```
rpm -Va
```

However, this gives me a big list and how did I even know it knew about all the broken packages? Maybe some pieces are in place so it things it's ok when it's not? While pondering this I came across a way to tell `yum` to reinstall every package it knows about installed on the host! This seemed to be a good path, I gave that a go, and while it had a ton of packages to reinstall, I kicked it off:

```
yum reinstall \*
```

And once it was completed, everything just worked again! It feels like a punt, but hey, it worked - and nobody had to know... well until now, but I only shared to help someone else, so there you go. As always, have fun out there, but be careful!
