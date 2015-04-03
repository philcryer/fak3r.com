---
title: "HOWTO: ssh tunneling for fun and profit"
slug: "howto-ssh-tunneling-for-fun-and-profit"
date: "2007-04-16T12:34:28-06:00"
author: "fak3r"
categories:
- geek
- howto
tags:
- bsd
- hacker
- howto
---

![OpenSSH book](http://fak3r.com/wp-content/uploads/2007/04/0596000111_cat.gif)Recently I had an issue at work; while trying to transfer files between Unix hosts we were unable to hit the known scp port, but we could still hit the ssh port. All of this was occurring from home, late at night on a Saturday where I was the main technical point man to move/install these files. In the past I had done ssh tunneling, but never on the fly to fix something like this, so I cracked open my notes and did a quick Google search for a refresher.

The first we'll look at the basic syntax of the command to setup the SSH tunnel:

`ssh -L <local free port>:localhost:<local sshd port> -p <remote host sshd port> <remote host name>`

Where:



	
  * <local free port> is an unused high-number port on the local host

	
  * <local sshd port> is the ssh port on the local host

	
  * <remote host sshd port> is the remote host’s ssh port

	
  * <remote host name> is the remote host you want to tunnel to


So, for example, if I wanted to copy files from work to my homeserver (but scp/sftp wasn’t running there) I could still scp the file via the ssh tunnel to home. Here’s how I’d do it:

`ssh -L 5555:localhost:22 -p 2222 fak3r.com`

Then I’d point to the tunnel while I issue a command I’d like to direct to it, and give it a username that is valid on the remote host:

`scp –P 5555 fiile.txt bob@localhost:~`

The file would then be in the home directory for bob’s account on fak3r.com. So anything directed at my local port of 5555 would be tunneled via ssh to the remote host’s sshd port of 2222 all via the tunnel I setup on my localhost, whose sshd is running on the default port of 22.
