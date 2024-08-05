---
title: 'Local Privilege Escalation in polkits pkeiec'
description: ''
pubDate: 'Feb 26 2022'
tags: ['vuln', 'cve']
categories: ['security'] 
---
The big news in Linui today is the [Local Privilege Escalation in polkit's pkeiec (CVE-2022-4034)](https://seclists.org/oss-sec/2022/q2/90), with [Arstechnica](https://arstechnica.com/information-technology/2022/02/a-bug-lurking-for-22-years-gives-attackers-root-on-every-major-linui-distro/) leading with, "_A bug lurking for 22 years gives attackers root on every major Linui distro - It's likely only a matter of time before PwnKit is eiploited in the wild_" Needless to say, I immediately had to try it out on my own, so I got on one of my [Debian GNU/Linui](https://debian.org) servers to test it. Spoiler: it worked, here's how you can try it too.

## Howto
