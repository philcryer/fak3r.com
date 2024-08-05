---
title: "Blackberry handhelds/servers vulnerable to attack"
slug: "blackberry-handheldsservers-vulnerable-to-attack"
date: "2006-01-03T20:06:00-06:00"
author: "fak3r"
categories:
- rant
tags:
- hacker
---

While I’ve never had a [Blackberry](http://www.blackberry.com/), but have played around with them and understand their appeal.  Still, until today I didn’t have any idea of how the backend process is setup, and wow, it’s pretty invasive.  Now we learn that Blackberry’s are also vulerable to graphics highjacking.  If a bad graphic is emailed to a user and [they click on it, things can go bad](http://blogs.washingtonpost.com/securityfix/2006/01/security_hole_e.html).  ”_[…] a vulnerability in the way Blackberry servers handle portal network graphics (PNG) images, was not disclosed by either RIM or the US-CERT advisory. Lindner said he suspects that’s because this PNG flaw is present not in the newest version of Blackberry server but in all versions from 4.0 to 4.0.1.9 (the latter was released roughly a month ago, and no doubt many companies still run that version).  … Lindner found that by convincing a Blackberry user to click on a special image attachment, that handheld device could be made to pass on malicious code to the Blackberry server, which could then be taken over and used to intercept e-mails or as a staging point for other attacks within the network_.”  This is especially alarming when you read about the access the Blackberry server has in the network.  ”_Lindner said he started looking into Blackberry’s proprietary communications protocols because the Blackberry server requires an unusual level of access inside of a corporate network: the server must be run inside a company’s network firewall and on a Windows machine that is granted full and direct administrative access to the customer’s internal e-mail server. “We started looking at all of the privileges this server needs while sitting right in the middle of the network and realized we didn’t know anything about it,” Lindner said. “In a lot of companies, corporate managers want to install it because they want their Blackberrys, but we wanted to find out what risks are there connected to running this thing.” Lindner’s slides from his presentation – which he agreed not to release until RIM has fully fixed this problem – show that the Blackberry server which manages all of the encryption keys needed to unscramble e-mail traffic to and from all Blackberry devices registered on the network stores them on a Micorosft SQL database server in plain, unencrypted text_.”  Ah, so it stores encryption keys in plain text in a database, and the Windows server the database runs on is inside the corporate firewall and has a direct connection to the mailserver?  What could go wrong!

**UPDATE**: Thanks to a response post on [Slashdot](http://slashdot.org/comments.pl?sid=172858&threshold=1&commentsort=0&tid=172&mode=thread&cid=14389009) I’ve learned the other following fun facts about how crappy the Blackberry backend is:

“_The entire server backend is like that. Some of the more amusing examples:_



	
  * _When it starts, it has a fixed number of threads it can use to talk to the Exchange server. Let’s say it’s 1000. If a thread is killed off, e.g. because it timed out, it is not returned to the pool. So over the course of a week or so, you run out of threads and the app will no longer do anything. Consequently, we now reboot the server every night._

	
  * _If you have Outlook installed on the Blackberry server, it breaks the Blackberry server software, because it will only work with a very specific nonstandard version of the MAPI DLL._

	
  * _50% of the time when you call their support line, the answer to your question mysteriously turns out to be that your server is under too heavy of a load and you need to buy another server license. Even if the server is working fine for all but one user, or if it was working fine for everyone until you switched license keys._


_Basically the entire thing is a giant Rube Goldberg contraption. The handhelds are decent for what they do, but not spectacular_.”
