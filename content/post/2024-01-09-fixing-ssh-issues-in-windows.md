---
title: "Fixing SSH issues in Windows"
date: "2024-01-09T17:45:19-05:00"
Tags: ["windows", "ssh"]
Categories: ["howto"] 
draft: false
---
<div align="center">
	<figure>
		<img src="/2024/ssh-windows.jpg" alt="SSH and Windows">
		<figcaption>SSH and Windows, do we really have to? Well for work... yes. :sigh:</figcaption>
	</figure>
</div>

I've worked with [OpenSSH](https://www.openssh.com/) and [posted about my experiences with it](https://duckduckgo.com/?q=openssh+site%3Afak3r.com) for as long as I've been learning [Linux](https://www.linux.org/), so easily 20+ years. It's an amazingly powerful tool that I still use everyday on personal (all Linux and [BSD](https://en.wikipedia.org/wiki/Berkeley_Software_Distribution) hosts) and work (Windows laptops and workstations, and Linux hosts) systems. Now with everything that ubiquitous and powerful, there's always more to learn...and sure okay, to break, which is a way I learn. Always has been, always will be.

## The issue

At work, there has been **a few** I've messed up my ssh keys and configuration in Windows, which breaks [Visual Studio Code's](https://code.visualstudio.com/) ability to ssh to remote hosts to do development work on. I believe I caused this issue when I thought it'd be good idea to have all my ssh configs and keys in sync with all remote (Linux) hosts and my work laptop, of course running Windwows 10. To do this I did something like:
 
```
scp -r fak3r@REMOTE_HOST:~/.ssh ~/
```
 
...and then, using my (Napoleon Dynamite voice) "Computer hacking skills" I locked down permissions on my ssh keys like I have so many times in Linux:
 
```
chmod 0600 ~/.ssh/id_*
```
 
Cool right? Well, not in Windows. After I did this, everytime I tried to connect to a remote server via SSH in Code, the sshe key authentication would fail, with the terminal revealing the error:
 
```
@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
@         WARNING: UNPROTECTED PRIVATE KEY FILE!          @
@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
Permissions for 'C:\\Users\\fak3r\\.ssh\\id_rsa' are too open.
It is required that your private key files are NOT accessible by others.
This private key will be ignored.
Load key "C:\\Users\\fak3r\\.ssh\\id_rsa": bad permissions
```
 
..and it would fall back to using password authentication only. Me trying to lock down the files the way I know messed with what Windows wants to know about files. Fine!
 
## The fix
 
While there are supposed ways to fix permissions/inheritances using [Powershell](https://learn.microsoft.com/en-us/powershell/) (see the 'Untested fix' section below), I didn't want to mess with that, but I decided to only use Powershell to fix my issue. It can generate the new SSH key, copy it key to the remote servers, and then connect to each host in Code. **Spoiler: it worked.**
 
* To get started, I started Powershell, and used it to back up my currently worthless ssh directory:
 
```
cd ~
mv ~/.ssh ~/.ssh-borked
```
 
* Then I regenerated a new SSH key
 
```
PS C:\Users\fak3r> ssh-keygen -t ed25519 -b 4096
Generating public/private ed25519 key pair.
Enter file in which to save the key (C:\Users\fak3r\.ssh\id_ed25519):
Enter passphrase (empty for no passphrase):
Enter same passphrase again:
Your identification has been saved in C:\Users\fak3r\.ssh\id_ed25519.
Your public key has been saved in C:\Users\fak3r\.ssh\id_ed25519.pub.
The key fingerprint is:
---snip---
```
 
* Then I created and poplulated my ~/.ssh/config to point to the new key, my username and the hosts I need to connect to. Again: DO NOT do any chmod 0600 ~/.ssh/* action using git-bash or [cygwin](https://cygwin.com/), you'll be unhappy - trust me.
 
My current/new .ssh/config:
 
```
Host *
  User fak3r
  IdentityFile ~/.ssh/id_ed25519
 
Host dev-server
  HostName long-servername.domain.com
 
Host new-dev
  HostName new-dev-long-servername.domain.com
```

Pretty minimal but I want to get it working before I spice it up with the all the cool options; port forwarding, jumpbox examples, compression, etc.
 
* Now my ~/.ssh dir looked like this:
 
```
PS C:\Users\fak3r> dir .ssh
 
    Directory: C:\Users\fak3r\.ssh
 
Mode                 LastWriteTime         Length Name
----                 -------------         ------ ----
-a----          1/9/2024   3:18 PM            330 config
-a----          1/9/2024   3:11 PM            432 id_ed25519
-a----          1/9/2024   3:11 PM            113 id_ed25519.pub
```
 
* Next I copied my ssh key to each host, again using a more Windows native menthod instead of the ssh too ssh-copy-id, replacing {IP-ADDRESS-OR-FQDN} with Host or Hostname listed in ~/.ssh/config for each host I want to connect to:
 
```
type $env:USERPROFILE\.ssh\id_ed25519.pub | ssh {IP-ADDRESS-OR-FQDN} "cat >> .ssh/authorized_keys"
```
 
* Testing it
 
Now I was able to ssh to all of my remote hosts using my ssh key, so without any password prompt.
 
* Testing it, in Code
 
I started Code, opened a remote connection, choose the host, all with the expectation that, "It should work(tm)". Fortunately it did (insert smiley emoji)
 
## Untested fix
 
Online there was advice on how to re-orient permssions and inheritance on existing ssh keys you may have borked via Powershell, I didn't do this, but including it here in case it helps someone else who doesn't want to start fresh.
 
* In Powershell - grant explicit read access, ineritance, and ownership to your ssh key(s) to your user (note: the ssh key name may be differnt than id_ed25519, the old default was id_rsa plus you can technically name it whatever you want, so salt to taste) while removing access to other users on the host:
 
```
# Set Key File Variable:
  New-Variable -Name Key -Value "$env:UserProfile\.ssh\id_ed25519"
 
# Remove Inheritance:
  Icacls $Key /c /t /Inheritance:d
 
# Set Ownership to Owner:
  # Key's within $env:UserProfile:
    Icacls $Key /c /t /Grant ${env:UserName}:F
 
   # Key's outside of $env:UserProfile:
     TakeOwn /F $Key
     Icacls $Key /c /t /Grant:r ${env:UserName}:F
 
# Remove All Users, except for Owner:
  Icacls $Key /c /t /Remove:g Administrator "Authenticated Users" BUILTIN\Administrators BUILTIN Everyone System Users
 
# Verify:
  Icacls $Key
 
# Remove Variable:
  Remove-Variable -Name Key
```

## Conclusion

While I'm not a big Powershell user/fan, I can appreciate when it needs to be used. In this case I didn't want to risk using any Linux/*nix style commands that may confuse things, plus I only used Powershell to do one of my favorite things; get off a Windows host. (devil face emoji) Applesause.
