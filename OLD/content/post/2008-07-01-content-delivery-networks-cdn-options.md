---
title: "Content Delivery Networks (CDN) options"
slug: "content-delivery-networks-cdn-options"
date: "2008-07-01T13:27:33-06:00"
author: "fak3r"
categories:
- blah
tags:
- cdn
- files
- hosting
- provider
- websites
---

![The Internet is not a big truck!](http://lh6.ggpht.com/_DjDt_rQmRi4/RqqlGFfr5fI/AAAAAAAACGc/bcAiBOk8-rc/300px-Notabigtruck.jpg)Does anyone have any CDN experience they'd like to share?  At my gig we need to move about 80-100GB of files to another provider, because we're serving up ~8.5GB/day, and it's killing our internal bandwidth. (yes, we're going to segment this soon) We've considered things as basic as [GoDaddy](http://www.godaddy.com), but at 6.99$/month that has to just be file hosting, not a [CDN](http://www.google.com/url?sa=t&ct=res&cd=1&url=http%3A%2F%2Fen.wikipedia.org%2Fwiki%2FContent_Delivery_Network&ei=64RqSOCmGaDeiAHE_KDcCw&usg=AFQjCNFZPzl5z-8gUl3n42Lb3fhh1_t0BQ&sig2=44Z07leYMAsGKUD9zdu_zg) right?  A package for that amount of data at [Cachefly](http://www.google.com/url?sa=t&ct=res&cd=1&url=http%3A%2F%2Fwww.cachefly.com%2F&ei=AIVqSJKWPJa6iwH52ezwCw&usg=AFQjCNGfziN5PlhRXcpAp6Pu_gMUTyp5cg&sig2=UoxCSWvaRMyNrUuxnoDVkw) looks like it'd be around 99$/month.  Other things like Amazon's S3 are being priced out, but what about Akami, Level3, etc.  Any advice, guidance appreciated, we'll likely go with the easiest way for now, but come next year we'll need a real strategy for a global content system, be it a CDN or a distributed filesystem spread across clusters. (aw yeah, that's the stuff...)
