---
title: 'HOWTO setup your own virtual-private-network-with-tailscale'
description: "Tailscale provides a drop-dead easy way to setup your own virtual private network with wireguard"
pubDate: 'Nov 30 2023'
tags: ['vpn', 'howto', 'vpn', 'security']
categories: ['geek'] 
draft: true
---
<div align="right"><img src="2023/tailscale-logo.png" alt="Tailscale"></div>
Over the Summer, while setting up a new VPN setup for the annual [DEF CON](https://www.defcon.org/) conference, I came across [Tailscale](https://tailscale.com/). At first I thought Tailscale just provided an easy way to setup and manage [Wireguard](https://www.wireguard.com/), which is a fast, modern and secure VPN tunnel software. While it certain does provide that, there's so much more to it... so much so that I now run it everyday on my laptop, desktop, a few servers and even my phone. 






> Tailscale is a VPN service that makes the devices and applications you own accessible anywhere in the world, securely and effortlessly. It enables encrypted point-to-point connections using the open source WireGuard protocol, which means only devices on your private network can communicate with each other.




When I first looked into it, I found posts about how Tailscale makes managing Wireguard, which is the preferred way of setting up your own VPN now, OpenVPN is deprecated, slow and full of bloat



How Tailscale Makes Managing Wireguard Easy - YouTubeHow Tailscale Makes Managing Wireguard Easy - YouTube
How Tailscale Makes Managing Wireguard Easy



https://login.tailscale.com/login?next_url=%2Fadmin%2Fmachines

https://www.androidcentral.com/apps-software/tailscale-best-service-nas

https://tailscale.com/blog/free-plan/


https://www.techaddressed.com/tutorials/installing-pi-hole-debian-ubuntu/

https://tailscale.com/kb/1114/pi-hole/

https://github.com/castrojo/ublue/blob/main/bits/tailscale

```
#!/bin/bash
# Install tailscale

set -u

if [ ! -e /etc/yum.repos.d/tailscale.repo ]; then
    echo "Installing repo..."
    sudo curl -s https://pkgs.tailscale.com/stable/fedora/tailscale.repo -o /etc/yum.repos.d/tailscale.repo > /dev/null
    # Disable repo_gpgcheck, which doesn't work on Silverblue 36+
    sudo sed -i 's/repo_gpgcheck=1/repo_gpgcheck=0/' /etc/yum.repos.d/tailscale.repo
fi

# Check if tailscale is already installed
rpm -q tailscale > /dev/null
if [ $? -eq 1 ]; then
    echo "Installing tailscale package..."
    rpm-ostree install tailscale
    echo "Please reboot to finish installation of the layered package and re-run this script to enable tailscale."
    exit 0
fi

echo "Enabling tailscale (systemd)..."
sudo systemctl enable --now tailscaled > /dev/null

echo "Starting tailscale..."
sudo tailscale up
```