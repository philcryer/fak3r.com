---
title: "Distributing biodiversity data globally"
slug: "distributing-biodiversity-data-globally"
date: "2008-10-09T12:00:25-06:00"
author: "fak3r"
categories:
- geek
tags:
- biodiversity
- bittorrent
- bittorrent protocol
- community
- debian
- library
- networking
- open source
- performance
- systems
---

![](http://www.fak3r.com/wp-content/uploads/2008/10/bittorrent_logo.gif)My current project at work will take me far into next year, and that's good because I'm facing an unprecedented amount of data, that will only continue to grow.  Because of this I'm finally getting to put my money where my mouth is.  For years I've talked about my ideas and theories about how I could network disparate systems together and have them leverage each other to keep everything in sync.  So, while working with Open Source to push boundaries I seem to find more ways to do more complex things.  One basic idea that I'm working on now is that data sets are huge, and are only going to get huger (and hugerer) as time goes on, how to handle this has been solved a few different ways.  Usually it's someone like the [Internet Archive](http://www.archive.org/index.php) who have 1000s of computers networked together to share the data (they are using some parts of [hadoop](http://hadoop.apache.org/core/) for the distributed file system, and then [nutch](http://lucene.apache.org/nutch/) for search indexing) - but it's still working from one central point of failure.  I started doing research to find out how this has been solved before, and if my idea of building a [BitTorrent](http://en.wikipedia.org/wiki/BitTorrent_(protocol)) network was sound - and I found some great information to build on.  As I'm setting up my demo BitTorrent tracker in Debian, this info keeps me thinking of the best ways to implement my ideas.  Much of my progress is due to the very helpful advice of Paul at [Geograph Torrent Archive](http://torrents.geograph.org.uk/index.php), a project that has somewhat similar goals.<!-- more -->

First, a great first introduction of what bittorrent is, and what it accomplishes:


> "BitTorrent does not centrally manage resource allocation. Instead, each client attempts to maximise its download rate by controlling various protocol parameters. Clients make direct connections (using ports 6881-6889 by default) to one or more of the clients in the list, to exchange parts of the file. Direct connections between clients are duplex (bi-directional), and every client tries to maintain the greatest number of active connections. A client's refusal to upload temporarily is known as choking. Connections are choked to prevent leeching a situation where another client is downloading, but not uploading.

To maximize the number of duplex connections, clients reward each other by reciprocating uploads. So clients unwilling to upload will find their download rate dropping as other clients choke in response. Clients decide which connections to choke or unchoke by calculating the current download rate of each connection, once every ten seconds. The connection is left choked or unchoked until the next ten-second period is up. This fixed interval cycle prevents clients from rapidly choking and unchoking, causing network resources to be wasted. Finally, a client does an "optimistic" unchoke, once every 30 seconds, to try out unused connections to determine if they might offer better transfer rates than current ones." [[link](http://www.technetra.com/writings/archive/2004/04/25/bittorrent-a-p2p-file-sharing-protocol)]


Now on to comments by others in how to use/harness this system for data propogation:


> "One idea that does linger in my head is the idea of creating a bittorrent distribution channel for library cataloging data. In the podcast, a concern was raised on whether a single server such as Library of Congress's might be serverely impacted if a lot of requests were made against its SRU server. If all the hopes of Casey Bisson's gift to the library community are realized, what if libraries were to contribute their individual cataloging and authority records to a global torrent? Again, I don't see the value of a single large file, like MIT's Barton data, over distribution of individual records. In the real world, torrent sharing is mostly at the work-level and that would seem to be the logical way to handle library records." [[link](http://www.tomkeays.com/blog/archives/2006/12/18/004216.php)]




> "Institutional repositories - I don’t hear as much noise about institutional repositories as I used to hear. I think their lack of popularity is directly related to the problems they are designed to solve, namely, long-term access. Don’t get me wrong, long-term access is definitely a good thing, but that is a library value. In order to be compelling, institutional repositories need to solve the problems of depositors, not the librarians. What do authors get by putting their content in an institutional repository that they don’t get elsewhere? If they supported version control, collaboration, commenting, tagging, better syndication and possibilities for content reuse — in other words, services against the content — then institutional repositories might prove to be more popular." [[link](http://infomotions.com/blog/2008/06/top-tech-trends-for-ala-summer-08/)]




> "Of particular interest here is how such a description of BitTorrent intersects on certain points (which I have emphasized above) with larger conversations and concerns campus IT organizations are currently having regarding the increasingly prohibitive costs of owning, maintaining, and monitoring data services locally. In fact, this an issue with much larger scope that is not limited to the education sector by any means. Much of this is a result of our particular moment wherein a plethora of externally hosted options provide college communities the same, if not better, services with infinitely more storage space. And all of this at a fraction of the cost. For some campus IT shops in the business of supporting themselves financially, or even making money, the risks of not going in such a direction are much more dire. The recent news that the University of Washington’s IT department will be laying off 15% of their staff speaks directly to this. In fact, a number of schools have already begun offloading IT staples such as file storage and email to externally hosted solutions. Arizona State University was one of the the first large universities to do this in a deal with Google back in the Fall of 2006, and it is a trend we will continue to see much more of in the coming months and years, particularly as budgets shrink and the economy continues to tank." [[link](http://bavatuesdays.com/bittorrent-an-educational-autopsy-of-the-hydra/)]




> "[...] bioinformatics networks present unique networking challenges that typically can't be addressed by generic network installations. The first is that there is a huge amount of data involved. The network isn't handling short e-mail messages typical of the corporate environment, but massive sequence strings, images, and other data. In addition, unlike networks that support traditional business transaction processing, data are continually flowing from disk arrays, servers, and other sources to computers for processing because the data can't fit into computer RAM. As a result, the network and external data sources are in effect extensions of the computer bus, and the performance of the network limits the overall performance of the system. It doesn't matter whether the computer processor is capable of processing several hundred million operations per second if the network feeding data from the disks to the computer has a throughput of only 4–5 Mbps." [[link](http://www.informit.com/articles/article.aspx?p=32102)]
