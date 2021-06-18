---
title: "Secure Linux Servers by Default"
date: "2021-06-18T10:44:18-06:00"
Tags: ["linux", "server", "infosec", "ansible"]
Categories: ["howto"]
---

<p>I get beyond upset when I come across a Linux server running at a company that is either not configured securely, or so out of date that I have to lookup what year the kernel was from. In this environment where companies continuously get pop, leak data and get compromised, I have zero tolerance for either of these senarios. Of course anytime I come across this I start asking questions, I talk to other teams (since whoever I talk to always has to pass the buck to some other team), and then when I finally get an explanation it's usually along the lines that their waiting for the vendor to update the image, that they'll then upgrade to. Example, an Amazon Web Services AMI.. the base image they build is the one the team installs, and they don't modify or update this image... ever. This is unacceptable, all of this security theater is leaving Linux servers vulnerable of too much passing the buck, or let's be honest, people that are running the servers don't really know how to run the servers. I've long projects like [DevSec Hardening Framework](https://dev-sec.io/), which I love because they address of slew of best practices pulled from all sorts of sources, and allow the automatic updating of these configurations via an automation tool like [Ansible](https://www.ansible.com). From this I built a project that will automatically install Ansible, apply these best practices to the base Linux system, but also setting up a far more secure [OpenSSH](https://www.openssh.com/) setup. My thought is this should be the first thing run against a new server, and then use that as the 'base' for all servers. I call this project `base-secure` as a play on something we used to say back in in the online Quake II days, which was a throwback to an earlier game, assume DOOM. 
<img align="right" src="/2021/block.png" alt="block" title="block" height="100" width="100">
Regardless, like everything it could be improved and changed, but for now, this works, and I'm happy to build on it and try to get others to use and promote the idea of running SECURE BY DEFUALT LINUX SERVERS!</p>

<!--more-->

<div align="center" border="0"><img src="https://raw.githubusercontent.com/philcryer/base-secure/main/src/base.jpg" alt="base secure?"></div>

# base-secure

Base-secure uses [Ansible](https://www.ansible.com) to automate the hardening of the Linux OS, and its SSH configuration using code from the [DevSec Hardening Framework](https://dev-sec.io/), which maintains a set of open source templates originally developed at [Deutsche Telekom](https://www.telekom.de/start). The goal of that project is to cover most of the required hardening checks based on multiple standards; including [Ubuntu Security
Features](https://wiki.ubuntu.com/Security/Features), [NSA Guide to Secure Configuration](https://apps.nsa.gov/iaarchive/library/ia-guidance/security-configuration/), ArchLinux System Hardening and others. After that it does a full system upgrade, using the [thorian93.ansible-role-upgrade](https://github.com/thorian93/ansible-role-upgrade) Ansible playbook, which verifies the host is running the latest kernel and that all software is up to date. I highly recommend this be run against a fresh
Linux host, then reboot, and use that as your new base for new servers; be it a virtual machine, an Amazon Machine Image (AMI), or bare metal.

## Rational

TL;DR I get upset when I find Linux servers that are not setup well. This aims to fix that.

## Features

* Hardens the Linux OS and its SSH configuration using the extensive [DevSec Hardening Framework](https://dev-sec.io/) Ansible Playbooks. (NOTICE: currently using their default setttings, you could lock this down further). These project regularly tests on Ubuntu, Debian, RHEL, CentOS, Oracle Linux, and OpenSuse
* Does a full system upgrade of all installed components using the [thorian93.ansible-role-upgrade](https://github.com/thorian93/ansible-role-upgrade) Ansible playbook
* Installs Ansible automatically if it's not installed (recommended)
* All pip installed packages (including Ansible) installed to user installation
* Clean function removes all downloaded Ansible files and uninstalls all pip installed packages, leaving nothing behind

## Requirements

* Linux (tested on Debian GNU\Linux, Arch Linux (EndeavourOS))
* python3 (tested with v3.9)
* curl
* sudo
* git

## Usage

Checkout the code, change into the directory:

```
git clone https://github.com/philcryer/base-secure.git
cd base-secure
```

Run it

```
./base-secure
```

## Cleanup

As with any server, you shouldn't have extranious things installed, so this script cleans up after itself and removes all downloaded Ansible files and uninstalls all pip packages (yep, even pip itself, if the script installed it) To do this, just run `base-secure` with the clean argument:

```
./base-secure clean
```

Hit the [GitHub](https://github.com/philcryer/base-secure) page for the code. Feedback EXTREMELY appreicated either on that page, or my twitter, where I'm still `@fak3r`

