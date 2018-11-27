
# 

## Software 

### Ansible

Debian users may leverage the same source as the Ubuntu PPA.

add-apt-repository 'deb http://ppa.launchpad.net/shimmerproject/ppa/ubuntu quantal main'

Add the following line to /etc/apt/sources.list:

deb http://ppa.launchpad.net/ansible/ansible/ubuntu trusty main

Then run these commands:

$ sudo apt-key adv --keyserver keyserver.ubuntu.com --recv-keys 93C4A3FD7BB9C367
$ sudo apt-get update
$ sudo apt-get install ansible

### Docker

apt update

apt-get install \
     apt-transport-https \
     ca-certificates \
     curl \
     gnupg2 \
     software-properties-common

$ curl -fsSL https://download.docker.com/linux/debian/gpg | sudo apt-key add -


add-apt-repository \
   "deb [arch=amd64] https://download.docker.com/linux/debian \
      $(lsb_release -cs) \
         stable"


apt update

apt-get install docker-ce

test

suod $ docker run hello-world


setup upser

$ sudo groupadd docker

$ sudo usermod -aG docker $USER

(log out, log back in)

docker ps

docker run hello-world

## Install Docker-compose

udo curl -L "https://github.com/docker/compose/releases/download/1.23.1/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose

sudo chmod +x /usr/local/bin/docker-compose

$ docker-compose --version






wget https://github.com/mistio/mist-ce/releases/download/v3.3.1/docker-compose.yml

