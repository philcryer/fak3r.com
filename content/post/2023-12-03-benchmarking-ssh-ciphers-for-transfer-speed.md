---
title: "Benchmarking SSH ciphers for transfer speed"
date: "2023-12-03T12:24:21-05:00"
Tags: ["ssh", "tranfers", "optimization"]
Categories: ["geek"] 
draft: false
---
<div align="right"><img src="/2023/ssh.jpg" alt="SSH"></div>

I transfer files via [OpenSSH](https://www.openssh.com/) all the time using scp manually or in scripts to move files between systems or servers, and with files getting larger all the time, I’m always interested in making transfers faster. Since I’m on my home network (with remote nodes securely connected over a [Tailscale](https://tailscale.com/) network (watch for an upcoming post on that)), I’m happy to sacrifice a little security (encryption) to gain some speed, so I set out to benchmark SSH transfers to find the fastest cipher in terms of performance to use for SSH transfers. Years ago we’d default to blowfish or arcfour, but since those don’t offer much security, newer versions of OpenSSH don’t include those anymore. I've always enjoyed exploring the benefits of Security vs. Convenience, and this seems like a good one to do!

So first up I wanted to see what ciphers were available to the version of OpenSSH I was running, and on Debian 12 it is currently: `OpenSSH_9.5p1, OpenSSL 3.1.4 24 Oct 2023`

Getting a list of cipher is a simple query:

```
> ssh -Q ciphers
3des-cbc
aes128-cbc
aes192-cbc
aes256-cbc
aes128-ctr
aes192-ctr
aes256-ctr
aes128-gcm@openssh.com
aes256-gcm@openssh.com
chacha20-poly1305@openssh.com
```

That’s easy enough, but of course I don’t want to manually test each of those, so I looked for a quick way to automate the task. I found a post on [Systuorials](https://www.systutorials.com/improving-sshscp-performance-by-choosing-ciphers/#comment-28725) where a user came up with a simple one-liner to do this. I liked the idea and the approach, negating a bunch of outside elements like network latency to just understand which cipher would be the fastest. Of course their solution included a list of ciphers for their environment, which failed when I ran it since my available cipers are different. To fix this I took their script, combined my ssh -Q query to dynamically populate the list of ciphers so anyone could use this, and it worked. I’m pretty happy with the resulting output, making it simple to figure out which cipher to call out when transferring files to make them a bit quicker.

Here is the one-liner, and notice that it only requires things that should already be installed on a Linux, macOS, or Unix-like host.

```
for i in $(ssh -Q ciphers | while read row; do printf "$row "; done); do dd if=/dev/zero bs=1000000 count=1000 2> /dev/null | ssh -c $i localhost "(time -p cat) > /dev/null" 2>&1 | grep real | awk '{print "'$i': "1000 / $2" MB/s" }'; done
```

When I run it on my system I get the following:

```
aes128-ctr: 529.101 MB/s
aes192-ctr: 526.316 MB/s
aes256-ctr: 512.821 MB/s
aes128-gcm@openssh.com: 534.759 MB/s
aes256-gcm@openssh.com: 523.56 MB/s
chacha20-poly1305@openssh.com: 239.808 MB/s
```

This tells us that aes128-gcm@openssh.com is the fastest cipher, so we just have to add -c aes128-gcm@openssh.com to the scp command to use that cipher.

```
scp -c aes128-gcm@openssh.com -r some_file.tar.gz fak3r@remote_host:~/transfer
```