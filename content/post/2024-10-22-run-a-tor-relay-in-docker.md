---
title: "Run a Tor Relay in Docker"
date: "2024-10-22T07:45:12-05:00"
Tags: ["tor", "docker", "online-privacy"]
Categories: ["howto"] 
draft: false
---
<figure>
<div align="center" />
    <img src="/2024/tor-bridge.jpg" alt="Tor bridge and other network connections" /><br />
    <figcaption>A generalized view of Tor networking</figcaption>
</div>
</figure>
 
## Summary

I used to run a [Tor Relay](https://community.torproject.org/relay/) from my personal servers, but it always took some time to setup/configure and maintain it. After watching a recent panel from The Tor Project, ["Distribute(d) Trust – The key to global encryption access"](https://www.youtube.com/live/r-wgsjyQlLs) I decided to spin one up again, but since today I use [Docker](https://www.docker.com/) for so many things, I looked for a way to utilize that. Well, they make it easy, so here's how to run a Tor Relay in Docker.

## Why

If you're not familar with Tor you may be wondering, why would I want to run a Tor Relay? EFF (the Electronic Frontier Foudation) has a great post about the benifits that [Tor Relays](https://www.eff.org/pages/what-tor-relay), as well as the Tor network, basically, "Tor is a service that helps you to protect your anonymity while using the Internet. Tor is comprised of two parts: software you can download that allows you to use the Internet anonymously, and the volunteer network of computers that makes it possible for that software to work." So to make the network stronger, we can run a Tor Relay, before you do you should understand what a [Tor Relay provides](https://blog.torproject.org/new-guide-running-tor-relay/), as well as the EFF's [Legal FAQ for Tor Relay Operators](https://www.eff.org/pages/legal-faq-tor-relay-operators). Essentially Tor can route traffic through your relay, technically an obfs4-bridge, but since it uses the source IP from the Tor Exit Node, not your bridge's IP, you won't get complaints from your upstream provider. Exit Nodes are very important but I don't recommend you run them at home, I've run Tor bridges off and on for many years from home and various [VPS Hosting](https://cloud.google.com/learn/what-is-a-virtual-private-server) providers, and have never had a problem or complaint. As always IANAL and YMMV, so know your rights.

## Howto

### Install requirements

First we need to install the software required to run this

* Install [Docker](https://docs.docker.com/get-started/get-docker/), I recommend running it in [Linux](https://docs.docker.com/desktop/install/linux/), but it can be easily installed in [macOS](https://docs.docker.com/desktop/install/mac-install/) and [Windows](https://docs.docker.com/desktop/install/windows-install/)
* Install [Docker-compose](https://docs.docker.com/compose/install/) 
 
### Setup steps

Now we'll follow [Tor's official Docker steps](https://community.torproject.org/relay/setup/bridge/docker/), so open a Terminal in Linux or macOS, if you're in Windows I recommend the realtively new [Windows Terminal](https://apps.microsoft.com/detail/9n0dx20hk701) from Microsoft and follow along

1) Create a directory to hold the Tor Relay Docker project, you could do it differently, but I create a path/directory like this

```shell
mkdir -p ~/Docker/obfs4-bridge
```

2) Change into that directory, or whatever path/directory you created

```shell
cd ~/Docker/obfs4-bridge
```

3) Download `docker-compose.yml` which tells Docker how to run the bridge, I'll use `curl`, but you could just [download the file](https://gitlab.torproject.org/torproject/anti-censorship/docker-obfs4-bridge/raw/main/docker-compose.yml) and place it in this directory

```shell
curl -OL https://gitlab.torproject.org/torproject/anti-censorship/docker-obfs4-bridge/raw/main/docker-compose.yml
```

### Configure

In order to define the variables need to run this we need a file in this dictory for the environmental variables we will use for this project, again I'll use curl but you could load this URL and save the file with a browser

```shell
curl -OL https://gitlab.torproject.org/tpo/anti-censorship/docker-obfs4-bridge/-/raw/main/.env
```

Now we'll edit that file, adding the 3 variables needed to run this; the OR_PORT, the PT_PORT (the obfs4 port), and make sure that both ports are forwarded in your firewall to the host running the relay. If you don't know how to do this, search for port-forwardig or how to run NAT on your brand of router or wifi access point. Adding your email address, allows the Tor project or others to get in touch with you if there are problems with your Relay.

> ![NOTE]
> The ports are somewhat arbitray, but avoid port 9001 because it's commonly associated with Tor and censors may be scanning the Internet for this port

```shell
# Set required variables
# Your bridge's Tor port.
OR_PORT=9099
# Your bridge's obfs4 port.
PT_PORT=9050
# Your email address.
EMAIL=me@somewhere.com
# If you want, you could change the nickname of your bridge
#NICKNAME=DockerObfs4Bridge
# If needed, activate additional variables processing
#OBFS4_ENABLE_ADDITIONAL_VARIABLES=1
# and define desired torrc entries prefixed with OBFS4V_
# For example:
#OBFS4V_AddressDisableIPv6=1
```

You can use [this service](https://bridges.torproject.org/scan/) to test if your port is forwarded correctly and if the obfs4 bridge port is reachable to the rest of the world.

### Run

Now we can run the bridge and see if everything works. To have docker-compose pull the Docker image and run it

```shell
docker compose up -d
```

Now we can watch the logs to see if everything looks right and it can connect to the Tor network

```shell
docker compose logs -f
```

You know you're successful if it bootstraps to 100%

```shell
[...]
obfs4-bridge-1  | Oct 22 18:42:15.000 [notice] The current consensus contains exit nodes. Tor can build exit and internal paths.
obfs4-bridge-1  | Oct 22 18:42:16.000 [notice] Bootstrapped 55% (loading_descriptors): Loading relay descriptors
obfs4-bridge-1  | Oct 22 18:42:20.000 [notice] Bootstrapped 60% (loading_descriptors): Loading relay descriptors
obfs4-bridge-1  | Oct 22 18:42:20.000 [notice] Bootstrapped 67% (loading_descriptors): Loading relay descriptors
obfs4-bridge-1  | Oct 22 18:42:20.000 [notice] Bootstrapped 72% (loading_descriptors): Loading relay descriptors
obfs4-bridge-1  | Oct 22 18:42:20.000 [notice] Bootstrapped 75% (enough_dirinfo): Loaded enough directory info to build circuits
obfs4-bridge-1  | Oct 22 18:42:21.000 [notice] Bootstrapped 90% (ap_handshake_done): Handshake finished with a relay to build circuits
obfs4-bridge-1  | Oct 22 18:42:21.000 [notice] Bootstrapped 95% (circuit_create): Establishing a Tor circuit
obfs4-bridge-1  | Oct 22 18:42:22.000 [notice] Bootstrapped 100% (done): Done
```

## Summary

You now have a Tor Relay setup and working, you can read some of the [post-install](https://community.torproject.org/relay/setup/bridge/post-install/) details including watching for users, and connecting to your bridge to access the Tor network from the [Tor Browser](https://www.torproject.org/download/), a Firefox based browser that can connect to Tor and open `.onion` links leading you to...

<figure>
<div align="center" />
    <img src="/2024/the-dark-web.jpg" alt="The dark web" /><br />
    <figcaption>The Dark Web!</figcaption>
</div>
</figure>
