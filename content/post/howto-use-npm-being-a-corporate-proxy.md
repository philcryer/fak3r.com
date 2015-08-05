+++
title = "HOWTO use npm behind a corporate proxy"
Description = "The continuing story about working behind 'security' proxies"
date = "2015-07-31T07:22:18-06:00"
Categories = ["howto"]
Tags = ["infosec", "firewall", "proxy"]

+++
# Overview

Working at __$big_company__ is not without its challenges, but the least of which should be network access, right? No, of course not. Installed "security appliances" (see the [SSL MiTM post](https://fak3r.com/2015/07/22/does-your-employer-run-ssl-mitm-attacks-on-you/) for more on that) on the network always limit access from within the corporate firewall out to the __I__ernet at large to protect from security vulurables. This is all great and fine, but that kind of protection always errs on blocking, so working with open source projects that are easy to install and run out in the real world become a nightmare when you're inside the coporate firewall.<!--more-->

## Issue

So today I was hitting a snag on installing [nodejs](https://nodejs.org/) apps via their package tool, [npm](http://github.com/isaacs/npm/issues). I knew the issue, we couldn't get out to the Internet because the proxy was blocking us. Setting the enviornmental variables to the proxy IP and port made no difference.

```
export http_proxy=http://1.1.1.1:3128/
export https_proxy=https://1.1.1.1:3128/
```

But even with those settings (that fix Internet access for other tools I use) it would fail like this:

```
$ npm install -g yo generator-hubot
npm http GET https://registry.npmjs.org/jshint
npm http GET https://registry.npmjs.org/jshint
npm http GET https://registry.npmjs.org/jshint
npm ERR! Error: tunneling socket could not be established, cause=getaddrinfo ENOTFOUND
npm ERR! at ClientRequest.onError (/usr/lib/node_modules/npm/node_modules/request/node_modules/tunnel-agent/index.js:159:17)
npm ERR! at ClientRequest.g (events.js:175:14)
npm ERR! at ClientRequest.EventEmitter.emit (events.js:95:17)
npm ERR! at CleartextStream.socketErrorListener (http.js:1487:9)
npm ERR! at CleartextStream.EventEmitter.emit (events.js:95:17)
npm ERR! at Socket.onerror (tls.js:1355:17)
npm ERR! at Socket.EventEmitter.emit (events.js:117:20)
npm ERR! at net.js:812:16
npm ERR! at process._tickCallback (node.js:415:13)
npm ERR! If you need help, you may report this log at:
npm ERR! http://github.com/isaacs/npm/issues
npm ERR! or email it to:
npm ERR! npm-@googlegroups.com

npm ERR! Linux 3.16.0-45-generic
npm ERR! argv "node" "/usr/local/bin/npm" "install" "-g" "yo" "hubot"
npm ERR! node v0.10.25
npm ERR! npm  v2.13.3

npm ERR! CERT_UNTRUSTED
npm ERR!
npm ERR! If you need help, you may report this error at:
npm ERR!     <https://github.com/npm/npm/issues>

npm ERR! Please include the following file with any support request:
npm ERR!     /root/npm-debug.log
```

## Trial, error, research, repeat

Digging around online showed how you could add proxy lines to your `.npmrc` file, so I did that, now my `.npmrc` looked like this:

```
proxy = http://1.1.1.1:3128/
https_proxy = http://1.1.1.1:3128/

```

and that still failed. More research online, another guess... oh, I mean an idea to try:

```
strict-ssl = false
ca = null
```

which makes sense; we have a firewall to protect us from the Internet, and to work around it we drop security features of the tool! Brilliant! But alas, that still failed.


## Solution

Finally I was told to hardcode the registry URL so that `npm` wouldn't have to look for it, so in the end, I had an `.npmrc` that looked like this:

```
proxy = http://1.1.1.1:3128/
https_proxy = http://1.1.1.1:3128/
strict-ssl = false
ca = null
registry = http://registry.npmjs.org/
```

While it's possible that not all of those lines are neccary, I'm not taking any chances; let's go with what works! And wow, it only took me an hour to get past this not all of the lines

# End

Reminds me that I need to host more stuff in my basement, aka __/r/homelab__ always so fun to hack and learn without such distractions.
