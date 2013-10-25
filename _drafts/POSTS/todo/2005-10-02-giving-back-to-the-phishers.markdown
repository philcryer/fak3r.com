---
author: phil
comments: true
date: 2005-10-02 18:39:00
layout: post
slug: giving-back-to-the-phishers
title: Giving back to the phishers
wordpress_id: 85
categories:
- spam
tags:
- phishing
- security
---

This past week I got another phishing email, and while they're annoying, I was especially annoyed that this one got through my spam and virus filters.  Generally the server stops them, with [ClamAV](http://www.clamav.net/) catching and blocking them before they squirm to my Inbox, but this one made it through.  It was another 'Activate your PayPal account!' style emails, with a link to a URL that started with mail.american.hu - so they didn't even add PayPal to the sub domian to at least try to make it look legit (ie- paypal.american.hu).  Still, the page looked real, and I'm sure people like my mom would feel she needed to login and find out what was up.  So, I hit Google to look for a way to report, or fight back, and was happy to find the tremendous site; [PhishFighting](http://www.phishfighting.com/).  Created by a frustrated web designer, all you do is cut/paste the URL of the offending phisher, and then PhishFighting takes over and posts, over and over, every 20 seconds with a randomly assigned bogus email/password combination.  Then it's easy enough to bookmark that page, and cut/paste it into new Firefox tabs so you can hit it multiple times.  I've emailed the admin of the page to find out if there's an acceptable usage (I don't want to slam his server) but so far I've sub'd a few thousand bogus entries just by opening a few new tabs in Firefox and leaving the computer running overnight.  I emailed him about seeing some of the source code, as I'd like to create a C or shell script to act as a client and do this automatically, outside of a browser.  Then I could create and host a web site to collect and verify phishing URLs, and have the client talk to the site to make sure it's list is fresh.  Hmmm...it could even update like ClamAV does, with a little script set to go off via cron to check for updates, that would be fly!  Want to try it out?  Why not use my current Phishers URL (pasted into PhishFighting of course) by [clicking here](http://www.phishfighting.com/Fighting.aspx?phType=Paypal&phURL=http%3A%2F%2Fmail.american.hu%2Ftravel%2Fcgi-bin%2Findex.php&Submit1=Go).  Do the center click on the link in Firefox and leave a few tabs humming away for awhile, it's a good feeling.
