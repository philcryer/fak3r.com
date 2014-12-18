title: building tiny docker images
date: 2014-12-08 20:03:11
tags:
---


## Overview
Most people running Docker are using huge images with needless applications installed and taking up space for their containers. Meanwhile, if you search in the Docker Registry for 'debian minimal' you'll come across some images that are over 260 MB! Whenever I build a new server I install Debian/GNU Linux doing a [Network install from a minimal CD](https://www.debian.org/CD/netinst/) which only includes the minimal base OS. My goal is to recreate this for Docker, starting with a base Debian install, and then removing things in that minimal install that are not needed by Docker, while leaving the basic *nix utilities in place. After this I use [docker-squash](https://github.com/jwilder/docker-squash) tool to free a few more bits in the container before committing and checking in the image here. 

## Usage
If  you just want to use this image, pull it and configure what you want:

```
docker pull philcryer/min-wheezy
```

To use this image in your own project, use this FROM line in your Dockerfile:

```
FROM philcryer/min-wheezy:latest
```

## Build
In the interest of Open Source and transparency, here are the steps I've taken to build this image. 

### Pull the official base Debian Wheezy image

*See [Debian Official Docker](https://registry.hub.docker.com/_/debian/) page for information on this container*

```
docker pull debian:wheezy
```

### Get the image ID and run the container

*Notice how small it is to start with*

```
# docker images | tail -n1
debian                      wheezy              f6fab3b798be        4 weeks ago          85.1 MB
# docker run -it f6fab3b798be /bin/bash
```

### Inside the container, run the following

```
apt-get update && apt-get upgrade -y 
apt-get clean -y && apt-get autoclean -y && apt-get autoremove -y
cp -R /usr/share/locale/en\@* /tmp/ && rm -rf /usr/share/locale/* && mv /tmp/en\@* /usr/share/locale/
rm -rf /var/cache/debconf/*-old && rm -rf /var/lib/apt/lists/* && rm -rf /usr/share/doc/*
echo "`cat /etc/issue.net` Docker Image - philcryer/min-wheezy - `date +'%Y/%m/%d'`" > /etc/motd
exit
```

### Commit those changes

```
# docker ps -a | head -n2
CONTAINER ID        IMAGE               COMMAND             CREATED             STATUS                      PORTS               NAMES
9fd99c47de64        debian:wheezy       "/bin/bash"         3 minutes ago       Exited (0) 12 seconds ago                       insane_newton

# docker commit  9fd99c47de64
3dd7dab3803b2bb507a576119ac3daf3de9f700cbf0e80127acc04a85891c0d4
```

### Squash the image and get its new ID

```
# docker save 3dd7dab380 | docker-squash -from root -t philcryer/min-wheezy | docker load

# docker images | head -n2
REPOSITORY             TAG                 IMAGE ID            CREATED              VIRTUAL SIZE
philcryer/min-wheezy   latest              243925c5b6ed        12 seconds ago       50.76 MB
```

### Push it to the Docker registry

```
docker push philcryer/min-wheezy
```

### Results

Pull the image and check the size:

```
# docker pull philcryer/min-wheezy:latest
Pulling repository philcryer/min-wheezy
243925c5b6ed: Download complete
511136ea3c5a: Download complete
dbb9d41e1af3: Download complete
Status: Downloaded newer image for philcryer/min-wheezy:latest

# docker images
REPOSITORY             TAG                 IMAGE ID            CREATED             VIRTUAL SIZE
philcryer/min-wheezy   latest              243925c5b6ed        2 minutes ago       50.76 MB
```

## Container details

Just for reference, this Docker container will contain the following.

### Kernel

```
# uname -a
Linux cbfd8af55155 3.16.0-4-amd64 #1 SMP Debian 3.16.7-2 (2014-11-06) x86_64 GNU/Linux
```

### Installed packages

```
# dpkg --get-selections
apt           install
base-files          install
base-passwd         install
bash            install
bsdutils          install
coreutils         install
dash            install
debconf           install
debconf-i18n          install
debian-archive-keyring        install
debianutils         install
diffutils         install
dpkg            install
e2fslibs:amd64          install
e2fsprogs         install
findutils         install
gcc-4.7-base:amd64        install
gnupg           install
gpgv            install
grep            install
gzip            install
hostname          install
inetutils-ping          install
initscripts         install
insserv           install
iproute           install
libacl1:amd64         install
libapt-pkg4.12:amd64        install
libattr1:amd64          install
libblkid1:amd64         install
libbz2-1.0:amd64        install
libc-bin          install
libc6:amd64         install
libcomerr2:amd64        install
libdb5.1:amd64          install
libgcc1:amd64         install
liblocale-gettext-perl        install
liblzma5:amd64          install
libmount1         install
libncurses5:amd64       install
libpam-modules:amd64        install
libpam-modules-bin        install
libpam-runtime          install
libpam0g:amd64          install
libreadline6:amd64        install
libselinux1:amd64       install
libsemanage-common        install
libsemanage1:amd64        install
libsepol1:amd64         install
libslang2:amd64         install
libss2:amd64          install
libstdc++6:amd64        install
libtext-charwidth-perl        install
libtext-iconv-perl        install
libtext-wrapi18n-perl       install
libtinfo5:amd64         install
libusb-0.1-4:amd64        install
libustr-1.0-1:amd64       install
libuuid1:amd64          install
login           install
lsb-base          install
mawk            install
mount           install
multiarch-support       install
ncurses-base          install
ncurses-bin         install
netbase           install
passwd            install
perl-base         install
readline-common         install
sed           install
sensible-utils          install
sysv-rc           install
sysvinit          install
sysvinit-utils          install
tar           install
tzdata            install
util-linux          install
xz-utils          install
zlib1g:amd64          install
```

## Feedback

This is one of my first Docker images (see all of them [here](https://hub.docker.com/u/philcryer/)) so please give me feedback in the comments. Thanks!
