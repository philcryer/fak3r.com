---
title: "Resolving LSIDs with URL resolvers and CouchDB"
slug: "resolving-lsids-wit-url-resolvers-and-couchdb"
date: "2009-04-29T08:29:04-06:00"
author: "fak3r"
categories:
- commentary
- featured
- geek
- headline
- linux
tags:
- CouchDB
- database replication
- link resolver
- LSID protocol
- network time protocol
- ntp
- Open Calais
- P2P
- php
- RDFa
- unix
- url resolver
- XML
---

![346483297_c4cb93ab4e_m](http://www.fak3r.com/wp-content/uploads/2009/04/346483297_c4cb93ab4e_m.jpg)Recently I've been looking at ways to solve some of biodiversities' long standing issues with [LSIDs](http://en.wikipedia.org/wiki/LSID), which are, "_Life Science Identifiers are a way to name and locate pieces of information on the web. Essentially, an LSID is a unique identifier for some data, and the LSID protocol specifies a standard way to locate the data (as well as a standard way of describing that data). They are a little like DOIs used by many publishers._"  I posted my thoughts to the [TDWG](http://tdwg.org) discussion mailing list on the topic, and am reprinting it here to allow for further community commentary; [Code4lib](http://code4lib.org), I'm looking at you.  While much of it is theoretical, it is doable, and if it covers all that needs to be addressed, would be a cool, sustainable way forward for link resolvers for all kinds of usage.


> I'm with Tim on this one, and taking one of Rod's other posts ("[LSIDs, disaster or opportunity](http://iphylo.blogspot.com/2009/04/lsids-disaster-or-opportunity.html)") a bit further, I think coming up with a simple, extend-able URL resolver would give us many benefits and allow LSIDs with extra, added information around them for all to use.  Looking at his example, a URL would get permanent tracking that would also post referrers, location and traffic.  A summary of the link could even be a page in itself, a cached version, a screenshot, or just a scrape of the code - pulling out the HTML tags, for future reference in case the real link goes down.  We could use the ability to create a customizable prefix (ie- http://someresolvr.com/bhl/SDFoijF), to somewhat follow DOI conventions, but could even save old DOIs or handles for historical purposes in a field attached to the new URL, or for reuse, making the new URL resolve to a current DOI with a simple post at the end of the new URL (ie- http://someresolvr.com/bhl/SDFoijF/DOI).  In the same way we could use user input, data pulled about the URL semantically to generate RDFa  (by using [pyRdfa](http://www.w3.org/2007/08/pyRdfa/)), then exposing that for all newly created URLS, and coming up with a standard to make it predictable (ie- http://someresolvr.com/bhl/SDFoijF/RDF).  The example at bit.ly shows the use of [Open Calais](http://opencalais.com/) to get more background information on the original link to provide more information, but it could also be pointed to other services we provide/use in biodiversity to provide a snapshot across the board of more context/content.  Users of the service could login to examine/add/edit the data by hand if desired, so they would still retain ultimate control over how their record is presented.  Thus, from a simple URL, we could build a complete summary that would build on what we're given while sharing it all back out.

Then the architecture (aka, the fun part) would be simple and distributed.  A webserver able to process PHP, running the database [CouchDB](http://couchdb.apache.org/) would be all that is needed to run the resolver.  CouchDB is schema-less, so the way it handles replication is very simple, and is built to be distributed, only handing out the bits that have changed during replication, as well as scale in this manner.  Having a batch of main servers behind a URL in a pooled setup (think of a simplified/smaller version of the Pool of Unix [networked time servers](http://www.pool.ntp.org/)) would allow a round-robin DNS, or a [ucarp](http://www.ucarp.org/project/ucarp) setup ("_urcarp allows a couple of hosts to share common virtual IP addresses in order to provide automatic failover_"), so if one main server went down, another would automatically take over, without the user needing to change the URL.  Plus, if we wanted to, to battle heavy usage of the main servers we could use the idea of Primary and Secondary servers as outlined in the pool.ntp.org model, so an institution with heavy usage could become a Secondary host and run their own resolver simply, with almost no maintenance.  They would just need the PHP files, which would be a versioned project, and then have a cron task to replicate the database from a pool of the main servers.  The institution's resolver could be customized to appear as their own, (ie- http://someresolvr.bhl.org/bhl/SDFoijF) and for simplicity could be read-only.  This way a link like http://someresolvr.com/bhl/SDFoijF could be resolvable against any institution's server, like http://someresolvr.bhl.org/bhl/SDFoijF or http://someresolvr.ebio.org/bhl/SDFoijF - as all of the databases would be the same, although maybe a day behind, depending on the replication schedule.  New entries would only be entered on a main server, or in 'the pool' (ie- http://pool.someresolvr.com/), then those changes would be in the database to be handed out to all on the next replication (I won't add my P2P ideas in this email - it may not be needed for the deltas that would need to be transfered daily or weekly).   Add to all of this that CouchDB is designed as "_...a distributed, fault-tolerant and schema-free document-oriented database_" which would fit into what we want to do; build a store of documents (data) about a URL that we can serve, while being a permanent, sustainable resolver to the original document.  If the service ever died, it could be resurrected from anyone's copy of the database (think [LOCKSS](http://www.lockss.org/lockss/Home) (Lots of Copies Keep Stuff Safe)), so that no data (original or accumulated) would be lost.  The data could be exported from the database in XML, and then migrated from that to a desired platform.

I have not been dealing with LSIDs as long as most on this list so I expect I'm glossing over (or missing) some of the concepts, so please let me know what I am lacking.  This is a needed service, and is a project I'd like to be involved in building.
