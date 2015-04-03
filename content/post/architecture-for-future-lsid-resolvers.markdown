---
title: "Architecture for future LSID resolvers"
slug: "architecture-for-future-lsid-resolvers"
date: "2009-08-18T15:32:20-06:00"
author: "fak3r"
categories:
- bioinformatics
- geek
tags:
- architecture
- distributed architecture
- dns
- lsid
---

**_![office-space](http://fak3r.com/wp-content/uploads/2009/06/office-space-150x150.jpg)NOTE: _**_the following is my generalized overview of some thoughts I came up with months ago in regards to LSID resolvers, and how to architect a fault tolerant solution ([LINK](http://fak3r.com/2009/04/29/resolving-lsids-wit-url-resolvers-and-couchdb/)).  I missed the meeting in Denmark last week (I was on a family vacation for once) where they were discussing this, and wrote the following for another attendee to submit on my behalf.  I'm posting it here for further exposure and discussion of the merits and shortcomings of these ideas.
_

"In thinking about the architecture for future LSID resolvers we need to remember that a single point of failure will fail. This has been proven true too many times, and it's clearly not the way to proceed if we want to build a system the community can rely on. To succeed we should follow successful implementations of distributed software services such as the domain name server (DNS), and the network time protocol (NTP). These succeed because the protocols are standardized and the software to connect to, and utilize, these services are simple to deploy.  If we had a package-able server that would connect to these services and be easy to deploy, an institution could ultimately count on its own server (with other servers providing automatic fail over) to serve as a resolver that would be updated against lead node servers regularly.<!-- more -->

Using the idea of DNS replication, we can understand how something like this could function. A primary server would serve servers below it passing out changes (deltas) such as adds and edits occur. An institution wouldn't have to check with a primary server to get resolution, it could talk to any of the servers that are in line for updates. Meanwhile having a batch of main servers behind a URL would allow pooling of resources just as the pool of Unix networked time servers all resolve around a single URL ([http://www.pool.ntp.org](http://www.pool.ntp.org)). The network time protocol (NTP) allows many nodes to resolve to the same address, so that if one server is unavailable, another one can provide automatic failover, without the need to change the URL.

After setting up this type of architecture all that would be left to do would be to expose the data on a server to others. Once other nodes were brought online and replicated against lead nodes, this would form the basis for a permanent and sustainable resolver system. If a main node ever died, it could be resurrected from other lead nodes, or even from an institution's copy of the current database, so that no data (original or accumulated) would be lost.  Using a distributed, fault-tolerant architecture with replication and protocols designed to handle these issues would allow LSIDs to become the true resource the community needs, and be sustainable for the long term with minimal administration overhead, and a low barrier for entry for would be contributors."
