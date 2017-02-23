+++
linktitle = "howto-use-systemd-to-control-vpn-connections"
featuredalt = ""
categories = [ "howto", "vpn"
]
description = ""
title = "HOWTO Use Systemd to Control VPN Connections"
featured = ""
featuredpath = ""
author = "fak3r"
date = "2017-02-22T14:24:42-06:00"

+++

Today I had a case where a coworker wanted a Linux server to connect to a particular VPN, and we didn't want to make it use some hacky way like putting a script in `/etc/rc.local` for it to run on boot. By using `systemd` we learned how to use it to control connecting to the VPNs, using the [OpenVPN client](https://openvpn.net/).

# Steps

## Install the OpenVPN client

### Verify the `openvpn` client is installed

* Debian/Ubuntu

```
apt-get install openvpn 
```

* RHEL/CentOS

```
yum install openvpn
```

## Get VPN keys, certifiates and configs 

* From your remote host, get the files or zip file that includes your VPN keys, certificates and configs 
* Place the files (unzipping any archives) into `/etc/openvpn` on your client

## Configure OpenVPN session

* In the directory `/etc/openvpn` copy the `.opvn` file to `.conf` (renaming .conf with the host or filename of the .opvn file - openvpn is looking for any .conf file in this directory)

```
cd /etc/openvpn
cp file.opvn file.conf 
```

The new `file.conf` will be identical to `file.opvn`, and will include all connection steps. It will look somemthing like this (yours will be different, but should have similar steps):

```
client
dev tun
proto udp
remote 106.132.15.101 1876
resolv-retry infinite
nobind
persist-key
persist-tun
ca ca.crt
cert default.crt
key default.key
tls-auth tlsauth.key 1
ns-cert-type server
cipher AES-128-CBC
comp-lzo
verb 4
script-security 3
route 10.122.17.0 255.255.255.0
route 10.122.120.0 255.255.255.0
```

### Enable this config in OpenVPN, so systemd can use it

* edit /etc/default/openvpn, and uncomment the following line

```
AUTOSTART="all"
```

* exit and save `/etc/default/openvpn`
* NOTICE: I'm just choosing `all` but if you had the files with different names you could call out specific ones. So for example, if you had `/etc/openvpn/worksucks.conf` you'd have:

```
AUTOSTART="worksucks"
```

haha!

### Reload / Restart services to use the new files

* Reload `systemd` daemon so it will pickup the changes

```
systemctl daemon-reload
```

* Restart OpenVPN so it will automatically connect to the VPN listed in `file.conf`

```
systemctl openvpn restart
```

### Debug

* check logs to verify everything worked

```
tail -f /var/log/syslog
```

* look for success messages, you should see something like:

```
Feb 22 13:01:03 localhost NetworkManager[996]: <info>  [1487790063.9729] manager: (tun0): new Tun device (/org/freedesktop/NetworkManager/Devices/43)
Feb 22 13:01:03 localhost ovpn-host[2915]: /sbin/ip addr add dev tun0 local 10.255.59.14 peer 10.255.59.13
Feb 22 13:01:03 localhost ovpn-host[2915]: /sbin/ip route add 10.122.17.0/24 via 10.255.59.13
Feb 22 13:01:03 localhost ovpn-host[2915]: /sbin/ip route add 10.122.120.0/24 via 10.255.59.13
Feb 22 13:01:03 localhost ovpn-host[2915]: /sbin/ip route add 10.102.59.0/24 via 10.255.59.13
Feb 22 13:01:03 localhost ovpn-host[2915]: /sbin/ip route add 10.255.59.1/32 via 10.255.59.13
Feb 22 13:01:03 localhost ovpn-host[2915]: Initialization Sequence Completed
Feb 22 13:01:03 localhost NetworkManager[996]: <info>  [1487790063.9801] devices added (path: /sys/devices/virtual/net/tun0, iface: tun0)
```

### Done

Now `systemd` is handling your VPN connections, and will keep them up for you. Notice you can stop/start them on demand, instead of just having them start at boot, by:

```
service openvpn@worksucks restart
```

Pretty fly, so that's it for now, have fun out there, but stay safe!
