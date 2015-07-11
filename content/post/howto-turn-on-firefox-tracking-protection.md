+++
title = "HOWTO Turn On Firefox Tracking Protection"
Description = "Determine the AMI ID for a CoreOS image from the commandline"
date = "2015-07-10T23:43:49-06:00"
Categories = ["howto", "geek"]
Tags = ["firefox", "dnt", "do not track"]

+++
Years ago there was a lot of excitment about Do Not Track (DNT) as a way to enhance online privacy for users by allowing them to 'opt-out' of tracking by websites and advertisers. The idea as defined on [Wikipedia](https://en.wikipedia.org/wiki/List_of_HTTP_header_fields):

> The Do Not Track header is the proposed HTTP header field DNT that requests that a web application disable either its tracking or cross-site user tracking of an individual user. The Do Not Track header was originally proposed in 2009 by researchers Christopher Soghoian, Sid Stamm, and Dan Kaminsky. Efforts to standardize Do Not Track by the W3C have so far been unsuccessful.
<!--more-->
In January 2011, [Firefox](http://mozilla-firefox.com/) became the first browser to implement this feature. So while it was a cool idea proposed by Soghoian and Kaminsky, two long running [DEF CON](https://defcon.org/) heros, the implementation lacked teeth - not on the browser side, but on the backends. While the DNT standard is in place, <i>"There are no legal or technological requirements for its use. As such, websites and advertisers may either honour the request, or completely ignore it."</i> ([source](https://en.wikipedia.org/wiki/Do_Not_Track#cite_note-bi-gapingflaw-25)) So we have to rely on advertisers (the ones the idea was supposed to target) to obey user's requests. Good luck. Leave it to [Mozilla](https://mozilla.org) to fix this in Firefox! They now include a [tracking protection](https://support.mozilla.org/en-US/kb/tracking-protection-firefox#w_how-to-turn-on-tracking-protection) option:

> Tracking Protection allows you to take control of your privacy online. While Firefox has a Do Not Track feature that tells websites not to monitor your behavior, companies are not required to honor it. Firefox's Tracking Protection feature puts the control back in your hands by actively blocking domains and sites that are known to track users. 

Unfortunately this feature is not in the main 'Preferences' section for Firefox, instead you have to go within the embedded config to set it. To enable Tracking Protection you have to open `about:config` in the location bar, search for `privacy.trackingprotection.enabled` and toggle the value to `true`. 
<div align="center"><img src="/2015/dnt.png" border="0" alt="Firefox settings"></div>

Besides actively blocking tracking, this supposedly speeds up browsing a bit too. Win, win. Thanks again to Mozilla, the only browser that *really* puts the user first, and hey, it's also the only open source browser out there.
