---
title: "Run a hidden tor website"
date: "2021-09-26T08:43:13-05:00"
Tags: ["linux", "server", "tor", "onion", "dark-web"]
Categories: ["howto"]
---

<img align="right" src="/2021/tor_icon.png" alt="Tor" title="Tor">

Today I'm going to walk you through setting up a simple [Tor](https://www.torproject.org/) hidden service, in this case, a webserver. I've played with Tor for a long time, but mostly using it as a client, not hosting a site. I wanted a fak3r version on Tor, so here we are.

I won't go into what Tor is, hit their [About](https://www.torproject.org/about/history/) page for that, but the software allows ANY user, "...to experience real private browsing without tracking, surveillance, or censorship." So think of the privacy missing on the 'normal' internet, well Tor subverts that by basically building a different Internet for you to travel on. Yes it can improve your standing even if you're hitting the 'normal' internet, but also opens up the ability to browse to .onion URLs, aka hidden services on "the dark web" (don't be afraid, it's just gotten a bad rap), which is what we'll build today.

I highly recommend you download the [Tor Browser Bundle](https://www.torproject.org/download/), which is a customized Firefox browser with Tor integration built-in, so you can surf to your new site and travel to other .onion sites.

<!--more-->

# Running a hidden service on tor

## Install Linux

First go install Linux on some host, VPS, or anywhere you want to run it. Get it working, config, lockdown as you normally would (or go the extra mile with my [Secure Linux Servers by Default](https://fak3r.com/2021/06/18/secure-linux-servers-by-default/) script), and then SSH to it.

## Install services

Notice: I'm running [Debian GNU/Linux](https://www.debian.org/), Bullseye:11, so if you're using another distro just install these applications however you would, the configs will mostly be the same.

## Install Tor

Become root, or use sudo, and install Tor:

```
apt update
apt install tor
```

### Configure Tor

Here we are going to modify the Tor config (torrc file), and add two lines to it. One is to the HiddenServiceDir (think of this as Tor's web docroot), and the HiddenServicePort, what Tor will listen on. In this example I'm going to go with port 80, the standard www port. Notice we're going to run our site on a Unix socket instead of a Port, since, even though we're staying on the same host, this should be more secure:

```
echo "HiddenServiceDir /var/lib/tor/hidden_www/
HiddenServicePort 80 unix:/var/run/tor_hidden_www.sock" >> /etc/tor/torrc
```

### Restart and enable Tor

Restart Tor to get it running and conected to the Onion network, this will also setup your new 'hidden' service! After that we'll enable Tor, so it will come up on boot:

```
systemctl restart tor
systemctl enable tor
```

### Get your new .onion link

Now that Tor is running and connected to the Onion Network, you can get the hostname (URL) it has given you. You need to be root, or use sudo, here, since it's lockekdown:

```
cat /var/lib/tor/hidden_www/hostname
ypc326vsdovry7dvgulcej67covlvx74nbndrvynuea5mqwz7rwzpaqd.onion
```

Notice, YOUR .onion link will be different, this is MINE, so don't use it, it won't work for you. Why? In that directory it also has the pub/private keys it created when it made that link, which stops anyone else from using it.

So make note of the .onion link, and move on.

## Install nginx

Become root, or use sudo, and install [nginx(https://nginx.org/), the webserver we'll use:

```
apt install tor nginx-full
```

### Configure nginx

To keep it simple, I'm just going to add my server block to the default, enabled site, so edit: /etc/nginx/sites-enabled/default. Notice: change the 'server_name' to your unique one that we discovered before:

```
server {
        listen unix:/var/run/tor_hidden_www.sock
	server_name ypc326vsdovry7dvgulcej67covlvx74nbndrvynuea5mqwz7rwzpaqd.onion;
	root /var/www/html;
	index index.html;
}
```

## Build a landing page

Now we'll host a simple page on your site just for you to see, if you have a site, or want to add one later, just throw it in /var/www/html - again, update your .onion link, and again, use sudo or root:

```
echo "Hello from ypc326vsdovry7dvgulcej67covlvx74nbndrvynuea5mqwz7rwzpaqd.onion on the Tor network!" > /var/www/html/index.html
```

### Restart and enable nginx

Restart nginx to pickup our config changes, and enable it so that it will start on boot:

```
systemctl restart nginx
systemctl enable nginx
```

## View your new Tor site

Now you're ready to view your own Tor site, of course if you use a normal browser to hit your .onion link it will fail, normal browsers can't hit .Onion links becaus it's not on the Tor netork. To do this, run the Tor Bundled Browser (Firefox), or even the new [Brave](https://brave.com) brwoser, which has some compelling, and intersting privacy features, and a built in Tor browser.

Once you've done that, hit other .onion links on the Tor network:

* [DuckDuckGo](https://3g2upl4pq6kufc4m.onion/) - the search engine you should be using
* [ProPublica](http://www.propub3r6espa33w.onion/) - news
* [Facebook](http://www.facebookcorewwwi.onion/) - keep in mind if you login to Facebook, they'll track you on their site as normal, but you're still better protected. It's also a great example of a big player seeing the need for Tor
* [Riseup](http://vww6ybal4bd7szmgncyruucpgfkqahzddi37ktceo3ah7ngmcopnpyyd.onion) - a site for privacy, activism, and so much more
* [The Hidden Wiki](http://zqktlwiuavvvqqt4ybvgvi7tyo4hjl5xgfuvpdf6otjiycgwqbym2qad.onion/wiki/index.php/Main_Page) - a wiki on Tor
* [Archive.today](http://archivecaslytosk.onion/) - archived documents and sites
* [The CIA](http://ciadotgov4sjwlzihbbgxnqg3xiyrg7so2r2o3lt5wz5ypk4sxyjstad.onion) - The CIA? Yep, it's all part of the history... "Tor has an unlikely history. It was first developed by the U.S. Navy to help informants posted in foreign countries to relay information back safely. In that spirit, the CIA launched an Onion site to help people around the world access its resources securely." And you though it was all illegal stuff, no.

Ok, that's it, surf around, look at your site, know that you can run anything you ran run with a normal webserver, so spin up a LAMP stack if you want, or anything else.
