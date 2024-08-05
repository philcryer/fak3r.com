---
title: "HOWTO: Speedup Firefox in 5 easy steps"
slug: "howto-speedup-firefox-in-5-easy-steps"
date: "2008-02-20T15:39:29-06:00"
author: "fak3r"
categories:
- geek
- howto
tags:
- about:config
- config
- firefox
- initialpaint
- maxrequests
- pipelining
- speed
- tweak
---

 so here's what you need to do to fix that.



	
  1. Type “about:config” into the address bar and hit return. Type “network.http” in the filter field, and change the following settings (double-click on them to change them):

	
  2. Set “network.http.pipelining” to “true”

	
  3. Set “network.http.proxy.pipelining” to “true”

	
  4. Set “network.http.pipelining.maxrequests” to 8 (recommended by Firefox devs)

	
  5. Right-click anywhere and select New-> Integer.  Name it “nglayout.initialpaint.delay” and set its value to “0″. This value is the amount of time the browser waits before it acts on information it receives.  With it set to zero the page just pops up, it's a dramatic change.


So give those a go, and don't stop there, there's plenty of [Firefox add-ons](https://addons.mozilla.org/en-US/firefox/recommended) to download and explore with; whatever you want to do online, there's likely an add-on that'll make it easier/better/faster.
