+++
title = "A shell script to check a webserver's cipher suites"
Description = "Quickly display a remote webserver's cipher suites"
date = 2018-06-25T17:15:31-05:00
Tags = ["linux", "ssl", "ciphers"]
Categories = ["howto", "hacking"]
+++

Today we wrote a simple shell script to query an SSL enabled webserver. Pretty fun to have in the aresenal, it looks like this:

<script src="https://gist.github.com/philcryer/5ce6746b1ec8487196d7e897425ee526.js"></script>

Let's run it against our site and see what we get:

```
$ ./ssl_cipher_test.sh fak3r.com
tls1_2: ECDHE-RSA-AES256-SHA
tls1_2: AES256-SHA
tls1_2: ECDHE-RSA-AES128-GCM-SHA256
tls1_2: ECDHE-RSA-AES128-SHA
tls1_2: AES128-GCM-SHA256
tls1_2: AES128-SHA
```

So what do you think?

<div align="center"><img src="/2018/not-bad.png" alt="Not bad"></div>
