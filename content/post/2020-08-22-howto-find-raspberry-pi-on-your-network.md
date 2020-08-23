---
title: "Find Raspberry Pi On Your Network"
date: "2020-08-22T18:21:13-06:00"
Tags: ["network", "nmap", "rasberrypi"]
Categories: ["howto"]
---
Anytime I'm trying out a new [Raspberry Pi](https://www.raspberrypi.org/) project, I need to find its IP once it's up on the network so I can access it. There are plenty of interesting ways to do this, but I always go with this simple way using nmap and awk. As always adjust as needed, for example if 192.168.1.0 isn't your local subnet.

```
sudo nmap -sP 192.168.1.0/24 | awk '/^Nmap/{ip=$NF}/B8:27:EB/{print ip}'
```
