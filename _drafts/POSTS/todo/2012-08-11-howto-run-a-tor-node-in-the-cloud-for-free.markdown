---
author: fak3r
comments: true
date: 2012-08-11 09:20:16
layout: post
slug: howto-run-a-tor-node-in-the-cloud-for-free
title: HOWTO run a Tor node in the cloud for free
wordpress_id: 3627
categories:
- geek
- privacy
- security
tags:
- amazon
- anonymus
- cloud
- ec2
- privacy
- s3
- server
- storage
- tor
---

[caption id="attachment_3628" align="alignright" width="200"][![Tor](http://fak3r.com/wp-content/blogs.dir/12/files/tor1.png)](http://fak3r.com/2012/08/11/howto-run-a-tor-node-in-the-cloud-for-free/tor-2/) Tor, a network of virtual tunnels that  improve privacy and security online[/caption]

**UPDATE 2 **a friend has posted an awesome overview of [Tips to running tor bridges](https://trac.torproject.org/projects/tor/wiki/doc/Tips_to_running_tor_bridges) on the Torproject.org site. Plenty of details so you really know what you're getting into, bandwidth and cost-wise when running your own Tor bridge. Great stuff!

**UPDATE **after running Tor on Amazon EC2 I have not been charged anything additional. Their 'free tier' seems to be just that, free. Nice.

I've run a [Tor](https://www.torproject.org/) node with some sense of seriousness for years, but last year I put it in the top tier of services I run and support, now keeping it up 24/7. This is one of the ways I give back to the [Electronic Frontier Foundation](http://www.eff.org/) (EFF) and support their work with the Tor team to protect our personal freedoms and privacy. When asked what Tor is it's hard to not get too technical, so here I'll defer to the official description, "_Tor is free software and an open network that helps you defend against a form of network surveillance that threatens personal freedom and privacy, confidential business activities and relationships, and state security known as traffic analysis. Tor was originally designed, implemented, and deployed as a third-generation [onion routing project of the U.S. Naval Research Laboratory](http://www.onion-router.net/). It was originally developed with the U.S. Navy in mind, for the primary purpose of protecting government communications. Today, it is used every day for a wide variety of purposes by normal people, the military, journalists, law enforcement officers, activists, and many others._" So *basically* it's a network of routed proxies that allow a reasonable amount of privacy and anonymity for anyone online. As expected the Tor software is open source, and the entire network is comprised of normal users, donating some of their bandwidth to make the network stronger. So with this post I want to show how you can run your own Tor node, in the cloud, for free*, and contribute to the networks viability.

<!-- more -->

I've run a tor node on my personal server for several years, it's at [tor.fak3r.com](http://tor.fak3r.com/), but I limit the bandwidth to a reasonable amount since it's sharing resources with other services. Now I have a dedicated instance running "in the cloud" on Amazon EC2 without any limits on bandwidth. It's dead simple to setup and run, even for someone non-technical. The Tor Project has setup a page, [Tor Cloud](https://cloud.torproject.org), which tells you all you need to know, meanwhile a blog post on the Torproject site, [Run Tor as a bridge in the Amazon Cloud](https://blog.torproject.org/blog/run-tor-bridge-amazon-cloud) explains why this approach is especially attractive when you consider, "To help new customers get started in the cloud, Amazon has introduced a free usage tier. The Tor Cloud images are all micro instances, and **new customers can run a micro instance for free for a whole year**. The AWS free usage tier also includes 15 GB of bandwidth out per month." I've run my EC2 node for ten months, and have had *charges totaling less than $2.00. To me that's free, we could limit the bandwidth usage so that it would never hit the limit, but to me this is close enough. Now that I'm almost out of the free trial period I'll find out what it is per month and report back.

If you have any questions about running a node feel free to comment below, message me directly or email the Tor mailing list. I spoke to EFF lawyers at [DEF CON](https://www.defcon.org/) 19, and the Tor devs before really getting serious about keeping mine available, but it's now 24/7 with alerts telling me if anything is amiss, just like I would a webserver or other critical service.




