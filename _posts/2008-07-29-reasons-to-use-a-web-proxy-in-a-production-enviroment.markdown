---
author: phil
comments: true
date: 2008-07-29 18:52:09
layout: post
slug: reasons-to-use-a-web-proxy-in-a-production-enviroment
title: Reasons to use a web proxy in a production environment
wordpress_id: 830
categories:
- commerce
- geek
tags:
- bandwidth
- enviroment
- im
- integrity
- internal traffic
- local network
- network topology
- performance
- public traffic
- reverse proxy
- security
- user experience
- varnish
- virus
- web
- web proxy server
- websites
---

**NOTE**: _at work I installed a web proxy to separate internal user traffic from external traffic hitting our prod__uction servers.  While I'm not part of the network team, they asked me to do this because of my prior experience and interest in such things.  The idea of this was to be a temporary fix until they get a new line installed providing greater bandwidth, but my argument is for the continuation of this segmentation even after the new line is installed.  Below is a slightly sanitized version of my arguments for this.  Note that my thoughts and comments are driven by years of running networks, thus it is something I care about and have spent years thinking about, so it is wordy.  I'd be very happy to discuss this, or other solutions, via the comments below because I never want to stop learning._

I'd like to share my thoughts in as to why I think the network is better served with keeping internal traffic and public traffic separate.  Regardless of if you use the existing web proxy server, or another one with different network topology, I care less about the tool, and more about making the network and user experience better for both internal and external users<!-- more -->****

**integrity**
First off, my strong feeling is that internal users should *never* be able to effect the performance of production websites (and to a slightly lessor extent vice versa).  Say for example we have a virus that bounces from email to email and sends out spurious requests via the Internet, this hammers our local network which kills the integrity of our production websites.  External users are left with an unusable system, and our integrity suffers.  On the flip side, a PR announcement goes out and traffic to our production sites surges, making not only our production sites slow, but also the local network for users trying to get email and look up things on-line.  Having all of these resources straining the same bandwidth pipe puts other things such as credit card verifications for sales in direct competition with a YouTube video download by an employee.  Sure a packet shaper can set priority for the credit card line, but why even have the competition?  Having these two types of traffic segmented from the start is a must to keep the integrity of our network, and our production running as well as they can without the chance of either one negatively effecting the other.  Additionally this setup cascades down to effect the other following aspects of how our network performs.  They are...

**security**
I've always been of the mindset that by default incoming ports are always set at "deny everything, allow only what is needed", something that virtually every firewall follows.  For the same reasons this should be the default behavior for any traffic originating here and heading out via the outgoing ports.  As far as I could tell, under the old gateway configuration, everything was allowed out (aside: even some protocols that people on the network side told me were blocked, were simply not) with the new gateway everything outbound was initially denied, and then we only opened things that 'needed' to be open.  In doing this we've shut down 1000s of ports that could otherwise be available for malicious program to utilize to breach our security from inside.  This is often how malware and viruses communicate their successful infestation and request further instructions.  Additionally, having a web proxy often stops this kind of communication because even a simple HTTP request initiated by the malware will not be configured to transmit through a web proxy; it will be expecting a straight shot out, since that's far more common.  This is one of the benefits of a traditional proxy over a so called 'transparent' proxy where no configuration is needed for the client to communicate externally; with a traditional proxy the malware will not know how it needs to be configured to transmit through the proxy.

**bandwidth**
A reverse proxy works by checking an HTTP request from a user against a cache of recently requested objects that the proxy stores.  This saves bandwidth since if a stored object (think of a file like a graphic, css, javascript, etc) is found it can be used instead of making another external request and download.  The current web proxy/gateway includes this functionality using the industry standard Squid, with no maintenance required.  It's simply better utilizing the resources automatically, so its use is a no brain-er here.  If we ultimately drop the web proxy I'm still going to be of the opinion that running a standalone reverse proxy like Squid, or my choice Varnish, would save incoming and outgoing bandwidth on our network.

**transparency**
While we got into this exercise because the network was slow; it was slow for internal users and slow for external users.  Now it's fast for internal users, with plenty of headroom to spare, and it will never infringe upon external users.  Any slowness of our sites can now be diagnosed without the concern of unknown network traffic effecting its abilities thus making troubleshooting and managing much simpler.  If we went back to the 'everyone in the same pool' setup, we'll be at the same mercy the next time the network is slow - how will we troubleshoot it then?  Pull the external users onto a new proxy so we can segment them out as a cause of the slowness?  With the network segmented we can already rule out one type of traffic being the cause of slowness.

**simplicity **
While a web proxy/firewall solution can be complex, the gateway in place is running Smoothwall, a specialized Linux install that is administrated fully through a web user interface.  It's simple to use and administrate as it is very well documented.  If I were to leave tomorrow I have no reservations that someone in the networking department could pick this up and run with it, no problem.  Other proxy solutions should be considered which could preform the same role with a similar level usability and upkeep.  The point is, we can do complex network configuration to best utilize our resources without having a specialist on hand to administrate it.

**conclusion**
I've installed a solution here that works at improving the network in a myriad of ways, and is doing the job without any day to day maintenance, however I would not be offended if another solution was suggested and used over mine.  My concern is not only for our network performance, but how our presence is perceived externally to the world as far as providing useful, reliable resources for information.

Extra credit for reading all the way to the bottom, and regardless of what decision is made, thanks for allowing me to present my solution, and my rational for it.
