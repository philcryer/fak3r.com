---
author: phil
comments: true
date: 2006-08-10 15:07:51
layout: post
slug: howto-passwordless-ssh-logins
title: 'HOWTO: Passwordless ssh logins'
wordpress_id: 149
categories:
- geek
- howto
- linux
tags:
- bsd
- howto
---

<img width="150" height="153" align="right" src="/assets/2006/openssh.png" border="0"> **UPDATE2:** _Recently, while building a proof of concept computer cluster, I came across a much simpler way to do this.  If you have ssh-keygen and ssh-copy-id installed, it's a two step process_

First, create a password-less ssh rsa key:

    ssh-keygen -b 2048 -f ~/.ssh/id_rsa -P ''

Second, copy the key to your remote host:

    ssh-copy-id user@remote.host

Note, if you're using a different port, or want to call out any other ssh features, put it in single quotes like this:

    ssh-copy-id '-p 2222 user@remote.host'

And that's it, easy cheesy. Might as well test it to make sure it worked:

    ssh user@remote.host

It should drop you to a prompt on the remote box without asking for a password.

* * *

**UPDATE:** _Apparently this has changed slightly, instead of writing to autorized_keys, you should use authorized_keys2 so any updates to the core OpenSSH won't mess up your 'local' keyfile (verus the system one).  Here are the correct (and more complete) directions:_


    ssh-keygen -t rsa

You shouldn't have a key stored there yet, but if you do it will prompt you now; make sure you overwrite it.

    Enter passphrase (empty for no passphrase): 
    Enter same passphrase again: 


We're not using passphrases so logins can be automated, this should only be done for scripts or applications that need this functionality, using a non-privileged user - it's not for logging into servers lazily! Don't make me come down there!

Now, replace REMOTE_SERVER with the hostname or IP that you're going to call when you SSH to it, and copy the key over to the server:

    cat ~/.ssh/id_rsa.pub | ssh REMOTE_SERVER 'cat - >> ~/.ssh/authorized_keys2'

Set the permissions to a sane level:

    ssh REMOTE_SERVER 'chmod 700 .ssh'

Lastly, give it a go to see if it worked:

    ssh REMOTE_SERVER

It should drop you to a prompt on the remote box without asking for a password.

* * *

My old, now deprecated, method:

    ssh-keygen -t rsa
    cat ~/.ssh/id_rsa.pub | ssh REMOTE_SERVER 'cat - >> ~/.ssh/authorized_keys'
    ssh REMOTE_SERVER 'chmod 700 .ssh'
    ssh REMOTE_SERVE
