+++
Categories = ["howto", "geek"]
Description = ""
Tags = ["howto", "lxc", "lxd", "containers", "ubuntu"]
date = "2015-04-23T19:13:29-06:00"
title = "HOWTO get started with lxc and lxd"

+++

Today [Ubuntu](https://ubuntu.com) released [15.04 (Vivid Vervet)](https://wiki.ubuntu.com/VividVervet/ReleaseNotes) which is a huge release for the lastest cloud and server options. Updated OpenStack, Juju, libvirt, qemu, Open vSwitch, Ceph, cloud-init, docker, corosync, haproxy, pacemaker - and the stars of the show, Ubuntu's take on the container world, lxc, lxd and Ubuntu Core, aka Snappy. With all of that fun stuff I didn't waste any time, I grabbed the server ISO of 15.04 and slapped it on a server. I got started with lxc and lxd to check them out, and while there's plenty more to do and learn, here's how to get started with them.

<!--more-->

[LXD](https://github.com/lxc/lxd) (pronounced lex-dee I've heard) is the Daemon based on liblxc offering a REST API to manage containers, specifically the [LXC](https://github.com/lxc/lxc) containers. To learn more, checkout [Linux Containers](https://linuxcontainers.org) is the umbrella project behind LXC, LXD, LXCFS and CGManager. So, let's get started, shall we?

## Install the software

Everything is in the repos, so if you're in 15.04 you should be good to go. Let's begin

```
apt-get update
apt-get install lxd lxd-client lxc lxc-templates python-lxc lua-lxc snappy system-image-snappy-cli
```

Boom, we're ready!

## Start me up

Start LXD to get the game going

```
service lxd start
```

## Prepare your user

This kind of setup seems to be something they'll automate later and have the installer handle, but until then, let's check some things. Your user should have a uid and gid map defined in `/etc/subuid` and `/etc/subgid`. The default should be 65536 for both the uids and gids, so you should be all set in Ubuntu - but if not, use usermod to set your user correctly. then, in `/etc/lxc/lxc-usernet (which is used to set network devices quota for unprivileged users) add a line like this:

```
your-username veth lxcbr0 10
```

This just means that "your-username" can create up to 10 veth devices to connect to the lxcbr0 bridge.

## Launch some containers

So enough of this, let's run some instnances. If you checkout [Linux Containers](https://linuxcontainers.org) you can learn about container names you can download and use, but for now we'll just use the basic ones.

* Debian 8 (Jessie)

```
$ sudo  lxd-images import lxc debian jessie amd64 --alias debian --alias debian/jessie
Downloading the GPG key for https://images.linuxcontainers.org
Downloading the image list for https://images.linuxcontainers.org
Validating the GPG signature of /tmp/tmpel_rn4fq/index.json.asc
Downloading the image: https://images.linuxcontainers.org/images/debian/jessie/amd64/default/20150421_22:42/lxd.tar.xz
Validating the GPG signature of /tmp/tmpel_rn4fq/debian-jessie-amd64-default-20150421_22:42.tar.xz.asc
Image imported as: 7bd9061a169dd5171469034963d1cf683550d28f8706b3a69a0715944e527984
Setup alias: debian
Setup alias: debian/jessie
```

* Ubuntu 15.04 (Vivid)

```
$ sudo lxd-images import lxc ubuntu vivid amd64 --alias ubuntu --alias ubuntu/vivid
sudo lxd-images import lxc ubuntu vivid amd64 --alias ubuntu --alias ubuntu/vivid
Downloading the GPG key for https://images.linuxcontainers.org
Downloading the image list for https://images.linuxcontainers.org
Validating the GPG signature of /tmp/tmphymwwli8/index.json.asc
Downloading the image: https://images.linuxcontainers.org/images/ubuntu/vivid/amd64/default/20150422_03:49/lxd.tar.xz
Validating the GPG signature of /tmp/tmphymwwli8/ubuntu-vivid-amd64-default-20150422_03:49.tar.xz.asc
Image imported as: 98ea382481a29de263d5657ed076893047c8917249ce01ad3b1f4c1f21e341b5
Setup alias: ubuntu
Setup alias: ubuntu/vivid
```

Easy cheesy, right? Let's take a look and see what lxc sees running:

```
$ sudo lxc list
+------------------------+---------+-----------+------+-----------+
|          NAME          |  STATE  |   IPV4    | IPV6 | EPHEMERAL |
+------------------------+---------+-----------+------+-----------+
| trafficless-garfield   | RUNNING | 10.0.3.38 |      | NO        |
| nondiphtherial-shawana | RUNNING |           |      | NO        |
+------------------------+---------+-----------+------+-----------+
```

Not bad, while I don't see all of the aliases I added, we clearly see two running! 

## TODO

Again, I'm just getting started with this, it seems very promising. There's obviously tons more to do, here are some of them. Once I do I'll update this post, but feel free to add what you know in the comments, or as a [pull request](https://github.com/philcryer/fak3r.com/pulls). Remember, _we're all in this together kid!_

* [Creating unprivileged containers as root](https://linuxcontainers.org/lxc/getting-started/)
* Entering a container, starting some services we can see from outside of the container
* [Run a system-wide unprivileged container (an unprivileged container started by root)](https://linuxcontainers.org/lxc/getting-started/) (note that root doesn't need network devices quota, instead using the global configuration file)
* Network containers together (something lacking in Docker, out of the box at least)
* Checkout orchestration options (same comment about Docker here)
* Run Snappy, see what can be done with it
* and more, I'm sure, stay tuned!
