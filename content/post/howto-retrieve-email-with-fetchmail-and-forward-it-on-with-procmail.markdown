---
title: "HOWTO retrieve email with fetchmail and forward it on with procmail"
slug: "howto-retrieve-email-with-fetchmail-and-forward-it-on-with-procmail"
date: "2011-07-07T22:40:56-06:00"
author: "fak3r"
categories:
- geek
- howto
- linux
- privacy
tags:
- email
- fetchmail
- fowarding
- imap
- mail
- procmail
- ssl
---



I'm starting a new gig Monday, so I got a new email address for use while I work there. Now of course, I have many, many email addresses, but thanks to [Google Apps](http://www.google.com/apps/intl/en/index.html), I still check them all through a Gmail frontend, and can 'send as' any address I want; which makes it almost seamless to integrate new email accounts. However, today we hit a snag, whereas my last client offered to simply forward my mail to another address, the new one wouldn't with something about auditing as their reason, which I can completely understand, as long as they understand, having to check email via multiple clients just won't scale. That's right Anthony, 'this won't scale'. So, since we're rocking Linux and open source we know we can fix it some way, and that's what I live for, the challenge. Ya, that's right, I was given a stumbling block, I stared it down and proclaimed, "challenge accepted".<!-- more -->[  


Some quick runs at Google told me what I already know, [fetchmail](http://fetchmail.berlios.de/) is king of the pond when it comes to grabbing mail from mailservers. It handles protocols POP2, POP3, RPOP, APOP, KPOP, all flavors of [IMAP](http://www.imap.org/), ETRN, and ODMR. It can even support IPv6 and IPSEC. Considering how I don't know all of those protocols I figure it can handle things. So on my server I install fetchmail, along with [procmail](http://www.procmail.org/), which will route the mail once I get it to my server.


    
    apt-get install fetchmail procmail



Next up I define a fetchmailrc in my home directory (${HOME}/.fetchmailrc) to tell fetchmail what to do anytime I call it.


    
    set no bouncemail
    defaults:
      antispam -1 
      batchlimit 100
    poll mail.domain.com with protocol imap
    user phil password ******* is phil
    ssl
    sslproto SSL3
    fetchall
    keep
    no rewrite
    mda "/usr/bin/procmail -f %F -d %T";



Some quick notes here, most is self-explanatory, but 'poll' calls the remote server using the 'user' and 'password' from the next line, then 'is phil' refers to the local user on the server. Using 'ssl' tells it to use an encrptyed connection and 'sslproto' forces it to use version 3. Then things like 'fetchall' tells it to look at all the emails (seen and unseen), 'keep' just keeps an email on the server (just until we're sure things are working). The last line is where the magic happens, once it has the mail it calls procmail to talk to your default mail transport agent (Debian uses exim4, yours may be postfix). Speaking of this we now tell Procmail what we want done, while it can be used for filtering with all sorts of regex fun, I'm going to define a simple .procmailrc recipe to just pass all of the mail it gets on to another address.


    
    :0
    * .*
    {
    	:0 c
    	$DEFAULT
    
    	:0 
    	!phil@mymainemail.com
    }



So I'm taking the mail, making a copy of it locally, called by the 'c' just after the first :0, (just until I know things are all on the level) and then forwarding another one to my 'real' email. And that's it, now I setup my user's crontab like this


    
    */5 * * * * /usr/bin/fetchmail >/dev/null 2>&1



so it will poll the IMAP server every 5 mins checking for new mail...and that's it. Now I'm setup to get email from the new gig, and I get to use the same UI I use for everything else. The cool thing, this sets me up for almost any future email accounts; if I can get email via POP or IMAP this will work for it.

As always, I can do this because I'm using open source, it just makes the impossible, posslbie, it's (relatively) easy, and just takes a little time to figure out a way to use existing tools to do the job.


