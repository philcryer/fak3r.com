---
author: phil
comments: true
date: 2008-08-05 13:46:38
layout: post
slug: black-hat-and-defcon-all-the-drama-youve-been-craving
title: 'Black Hat and Defcon: all the drama you''ve been craving'
wordpress_id: 818
categories:
- geek
- linux
tags:
- apple
- black hat
- blog
- dan kaminsky
- defcon
- design flaw
- integrity
- open source
- patches
- peer review
- security
- updates
- vulnerability
---

[![Dan Kaminsky - Security researcher with IOActive](http://www.fak3r.com/wp-content/uploads/2008/07/dan_kaminsky.jpeg)](http://fak3r.com/wp-content/uploads/2008/07/dan_kaminsky.jpeg)


This is great, [Defcon16](http://defcon.org) is a mere few days away, but already, the drama has started!  Of course there's the excitement about security guru/celebrity [Dan Kaminsky](http://fak3r.com/2007/08/02/security-researcher-dan-kaminsky/) discovering the [DNS flaw a few months back](http://latimesblogs.latimes.com/technology/2008/07/major-computer.html) that will be revealed this week (so that folks won't be able to reverse-engineer them to exploit the vulnerability...ahead of time at least), but now there's a reneg by Apple that's sure to raise a few feathers, as well as highlight how they weren't the most forthcoming with their DNS fix (which hasn't hit yet even though all other vendors have released patches).  In an interview, Kaminsky talks about the 'bug' he found in DNS, "_We got lucky in this particular bug, because it's a design flaw," Kaminsky said in an interview. "It shows up in everyone's network, but the fix is a design fix that doesn't point directly at what we're improving._"  After peer review it was deemed this was indeed a huge deal, and even the original developer of BIND (the dns software in question) urged everyone to patch.  "_It took a couple of hours to find the bug," said Kaminsky, "and a couple of months to fix it." Kaminsky said he stumbled across the hole in the so-called DNS system for steering people to the websites they are seeking "by complete and total accident." Smaller DNS flaws have been used before to "poison" the servers that send people to the numerical address of the website name they enter. [...] "This is about the integrity of the Web, this is about the integrity of e-mail," Kaminsky said. "It's more, but I can't talk about how much more._"  So learning more about that exploit will be very interesting, and should lead to more people investigating and deploying [DNSSEC](http://www.dnssec.net/), a DNS option built with security in mind from the ground up.  So there's that, but now there's something even more fun because it deals with a companies lack of openness in regards to their security methods.  A talk at [Black Hat](http://www.blackhat.com) yesterday was scrubbed at the last minute by folks over in marketing at [Apple](http://apple.com).  It seems that they [blocked the scheduled presentation](http://www.theregister.co.uk/2008/08/05/apple_nixes_black_hat_talk/) that was, "_...to give an inside look at the ultra-secretive company's security response team.  "Marketing got wind of it, and nobody at Apple is ever allowed to speak publicly about anything without marketing approval," a Black Hat organizer told IDG News._"  This is unfortunate for Apple, who are [reeling after a week of beatings](http://www.macworld.com/article/134793/2008/08/apple_dns.html?t=232) in the 'blogosphere' over their handling, or non-handling, of their update for the DNS flaw we mentioned above!  "_Apple's policy of saying next to nothing about how it goes about protecting its users from escalating threats is, to say the least, unfortunate. Just last week, the company said it had patched its software from a serious flaw in the net's address lookup system. Three days after two separate researchers warned Mac clients are still vulnerable to the flaw, Apple hasn't uttered a word, an omission that generates confusion and doubt in those who depend on the vendor. Apple's tight-lipped policy._"  Come on Apple, you preach about how you're 'Open Source', but then continue along the path of the old school hide and seek ways.  Hell, people are already pointing out how their methods are less open than Microsoft's in releasing information about security.  What are they so afraid of?  Ah, but we'll learn more come Thursday, I'll be in Vegas for my third Defcon and can't wait.  Watch for updates here, or more timely ones over at our [Twitter profile](http://twitter.com/fak3r).
