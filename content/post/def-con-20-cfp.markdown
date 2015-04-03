---
title: "' DEF CON 20 CFP'"
slug: "def-con-20-cfp"
date: "2012-03-27T22:08:07-06:00"
author: "fak3r"
categories:
- geek
- privacy
- security
tags:
- def con
- def con 20
- defcon
- defcon cfp
- defcon20
- proposals
- talks
---

[![DEF CON 20](http://fak3r.com/wp-content/blogs.dir/12/files/dc20-logo_smsq.png)](http://fak3r.com/2012/03/27/def-con-20-cfp/dc20-logo_smsq/)

**UPDATE**: _since yesterday was May 28th, I submitted this proposal late last night, along with another one covering a new project I'm toying with called [blackGATE](http://philcryer.com/projects/blackgate/), more on that in a bit. Below you'll see the extended outline that roughly sketches out what I'll be covering in the talk._

The [DEF CON© CFP](http://defcon.org/html/defcon-20/dc-20-cfp.html) (Call for Papers) is open now until May 28, 2012, so this year instead of writing up my CFP proposal on a wiki like I did last year, I'm just going to do it here and update it as I go until I submit it. This idea was presented to me by [@thepres98](https://twitter.com/#!/theprez98), who I enjoy reading, but as you can see he is a bit further along than me and already has his talk, [Flex Your Rights: The Constitution & Political Activism in the Hacker Community](http://theprez98.blogspot.com/2012/03/defcon-cfp-submission-flex-your-rights.html), up and ready for review. Last year I was honored to speak at DC19, my talk [Taking your ball and going home](http://fak3r.com/2011/08/17/defcon-19-taking-your-ball-and-going-home/) was well recieved and an amazing experience for me. After attending DEF CON for 5 years, this was only my 2nd proposal I submitted for a talk, and it was approved. After using [Dropbox](https://www.dropbox.com/) for a time and learning about their privacy issues, I started doing more research on the topic and started up [Lipsync](http://lipsync.info/) as an open source alternative. After encouragement on Twitter from [@_videoman_](https://twitter.com/#!/_videoman_) to just 'have fun with it', I wrote up an extended outline and submitted it. It's with this idea that I move forward with my propsal for this year, so I dreamt up a title page and got cracking; the working title is 0nline Privacy In The Year of The Dragon.

<!-- more -->

[![0nline Privacy In The Year of The Dragon](http://fak3r.com/wp-content/blogs.dir/12/files/dc_title.png)](http://fak3r.com/2012/03/27/def-con-20-cfp/dc_title/)


### Title




0nline Privacy In The Year of The Dragon





## Abstract




User’s privacy online is constantly changing, witness Google’s March 1st consolidation of their privacy polices, the ever changing Facebook privacy policies or how commerce determine the ways policy changes for other entities - then note the lack of any opt-out when these changes occur. The important thing here is that companies are doing this not for the benefit of the user, but for the benefit of the shareholders of the company, and if they can do this now, they can do this later, or whenever suits them. Simply, a fair user policy today can change tomorrow. These changes to policies or features are designed to make user’s data, and their interaction with the site, more profitable for the company. Knowing this should signal an alarm for everyone to understand HOW their data is being stored and USED. We'll look at recent developments this year that cause concern among privacy advocates, while poking fun at some of the silly ways these new measures are sold to the populace, then we'll cover what can be done, to increase users' privacy online with common sense and open source software.





## Introduction





	
  * echo "o'hai!"; `whoami`

	
  * I am a security researcher, open-source technologist, practicing trouble maker, DEF CON, SEMAFOR speaker




### The problem; user's loss of online privacy





	
  * How much should people worry about the loss of online privacy?

	
  * How much is a persons' privacy worth? Google, what do you think? (this one is good)

	
  * What are our online identities, who determines how they are presented/evolved -- "But these same trends raise questions about another topic that gets far less attention: our online identity. It's in this vital but less heralded area where Kaliya Hamlin, 35, aka "Identity Woman," has become one of the most influential voices in Silicon Valley." http://www.mercurynews.com/chris-obrien/ci_20116258/obrien-kaliya-identity-woman-hamlin-hopes-international-recognition

	
  * conclusion… If you're not paying for the product, you are the product. (so much for FREE email.. ;))




### Social Networks





	
  * Study Reveals What Social Networks May Know About Non-Members

	
  * scary stuff shows how who you know, even if you're not "linked in" to them, determine much about you and your preferences http://www.sci-news.com/othersciences/mathematics/article00289.html

	
  * The bizarre but true story of people in interviews for jobs being asked their Facebook passwords (unreal!)

	
  * Changes in policy have the Feds calling: Facebook and Google have made damaging errors in the minefield of personal information, privacy and business. Both have been sued for their use of customers’ data and have entered into agreements with the Federal Trade Commission (FTC) to change their practices http://www.business2community.com/tech-gadgets/time-for-a-privacy-policy-review-0142479

	
  * Privacy tussle brews over social media monitoring -- The U.S. Department of Homeland Security, the FBI and other agencies contend that social media monitoring is a vital part of their efforts to keep abreast of events that that could pose threats to national security and public safety. Privacy advocates maintain that unfettered social media monitoring by the government will chill free speech and intrude upon privacy and civil rights. The Electronic Privacy Information Center (EPIC) and other groups have noted that that at least some of the information harvested from social media sites by some government agencies has little to do with public safety goals. https://www.networkworld.com/news/2012/021612-privacy-tussle-brews-over-social-256260.html?hpg1=bn




#### Facebook





	
  * "By the end of the summer, it may have **more than a billion users, or about fifteen per cent of the world’s population**. Some of these people are restive and see Facebook as a substitute public space for speech and dissent that their own authoritarian regimes don’t provide. Facebook users have already helped to foment revolution in some places (Egypt and Tunisia) and are still trying, at great cost, to overthrow one of the Middle East’s most brutal regimes." http://www.newyorker.com/online/blogs/comment/2012/05/leaving-facebookistan.html#ixzz1w853THm6

	
  * 75%+ of users are non-US (this surprised me)

	
  * "Facebook may be forced to make changes to its data use policy after campaigners helped drive enough complaints about the company's own proposed amendments to trigger a user vote on the matter. http://www.theregister.co.uk/2012/05/25/facebook_data_use_policy_up_for_vote/

	
  * Clayton High's principal resigns amid Facebook mystery http://www.stltoday.com/news/local/education/clayton-high-s-principal-resigns-amid-facebook-mystery/article_70bd065a-5912-551a-ac73-746ea58177af.html#.T6bbDpFbq2V.facebook

	
  * Facebook changes privacy policy, implements timeline (with no OPT-OUT, short of deleting your account)




#### Google





	
  * In 1999, Scott McNealy, the former head of Sun MicroSystems, reportedly declared, "You have zero privacy anyway....Get over it." He unintentionally let the proverbial cat out of the bag of the digital age. In 2009, McNealy’s assessment was confirmed by Google’s CEO, Eric Schmidt. In an interview with NBC's Mario Bartiromo, he proclaimed, "If you have something that you don't want anyone to know maybe you shouldn't be doing it in the first place." Schmidt’s words have become Google’s new mantra. Welcome to 21st-century corporate morality. http://www.alternet.org/rights/155479/the_terrifying_ways_google_is_destroying_your_privacy/

	
  * Google to make searches smarter with new feature - how will this look at you differently, do you want it to? http://www.taipeitimes.com/News/biz/archives/2012/05/18/2003533084

	
  * google+ with the privacy ads fiasco (they were not being honest, but it's all about money)

	
  * Google privacy changes push need for multiple online personas (http://www.computerworlduk.com/in-depth/it-business/3342021/google-privacy-changes-push-need-for-multiple-online-personas/)

	
  * how will this effect business, their employees and how their opinions/data are exposed

	
  * March 1st terms of use, change across the board, effecting 80+ different policies, all standardized - for YOUR benefit?




#### LinkedIn





	
  * “...all these concerns about privacy tend to be old people issues.” Reid Hoffman, the founder of LinkedIn, in a segment during last year’s World Economic Forum at Davos, Switzerland

	
  * people's moves transmitted to the world (some small, some they don't want to share, but do)

	
  * my neighbor, how I knew she left her job before she told me!

	
  * my "See who you know" nightmare when I played the LinkedIn game

	
  * their "support" got back to me two weeks later to say, whoops, that shouldn't have happened.

	
  * moral, expect to be exposed, even if you ask not to be… m/k?




#### Other players





	
  * Spokeo, like a phone book, for 2012, and you're not going to like what it knows, or thinks it knows about you

	
  * again, you're not a name, you're a number, meet "Curate.me, formerly known as XYDO Brief, is making its public debut today after a 6-month invitation-only beta period which attracted some 20,000 users. Essentially, the service delivers personalized news to your email inbox based on your interests and data mined from your favorite social networks and news sources."

	
  * The news-via-email service, which reminds us of News.me, self-reportedly generates over 500,000 pieces of ‘content’ from Twitter, Facebook, LinkedIn and Google+ and the broader Web on a daily basis. http://thenextweb.com/socialmedia/2012/03/13/curate-me-mines-twitter-facebook-and-more-to-email-you-news-you-actually-want-to-read/

	
  * more, with a more direct "your privacy is our profit" profit model, warning - these will annoy you!

	
  * Beenverified.com -- Your data for sale (https://safeshepherd.com/beenverified) Over the past few months we've received a flood of complaints about the people-search website BeenVerified.com; our users had issues ranging from privacy violations to blatantly bad business practices.

	
  * Bing how are they stacking up versus Google? After all, MS has ads too, what have they done to monotone their user(s?) data? is the tracking as pervasive, what are their methods of sharing what they know (any type of dashboard from them? opt-out options?)




### File syncing/backup/sharing




#### Dropbox





	
  * old news, but replay it quickly…

	
  * the case, EFF involvement, Chris S's work on the topic




#### iCloud (Apple)





	
  * why should you be concerned about Lion/Mountain Lion's pushing everything to the (i)cloud

	
  * apple is a for profit company remember,

	
  * fruit is their logo - lock in is their game




#### the cloud.ly fail





	
  * cover my open source POC, ca-harvester (https://github.com/philcryer/ca-harvester) to show how to demonstrate the lack of privacy

	
  * responsible disclosure to the company, they acted with indifference, I released the tool two months later

	
  * things people store online that they think are private, simply are not




### What can be done to improve this?




#### Clean up our social exposure





	
  * You can delete posts on your Timeline (Facebook)

	
  * Set all privacy settings (Facebook, Google, all really)

	
  * use Google dashboard to block specific ads or to opt out of “advertising personalization” altogether

	
  * on Twitter choose “protect my tweets”, or just don't share, be a lurker, it's fun!




#### Browse safer





	
  * private browsing IS NOT PRIVATE! (show graphic, explain the BS that it perpetuates on the society at large)

	
  * encrypt DNS queries (openDNS, not just a desktop tool, run it in Linux, here's how I've done it…)

	
  * DO-NOT-TRACK - how are companies going to implement this, how will it help us? how will it help our parents, other non-techy folk




#### Know what you're sharing





	
  * do not track plus, ghostery

	
  * run these plugins to not only block tracking, but to educate yourself on how you/users are being tracked




#### Cleanup what you can





	
  * https://safeshepherd.com/

	
  * reputation.com

	
  * others that help identify privacy issues for users




#### Search smarter





	
  * DuckDuckGo (my day-to-day search now, it's come a long way baby)

	
  * ixquick (very private, secure)

	
  * yacy (use the yacy P2P search network, or run your own and be part of the solution)




#### File sharing





	
  * spider oak (a more secure, private way than dropbox)

	
  * lip-sync (see me after class - DIY, as always, FTW)

	
  * own cloud (share things remotely, but you hold the keys)

	
  * unheated protocol (from Germany, gaining traction)




#### Encrypt communications





	
  * https everywhere (thank you EFF!)

	
  * Tor (uh, ya, thanks AGAIN EFF!)

	
  * i2p anon network (more options are out there)

	
  * free net (it could still be a player, but again, there are others out there)

	
  * blackGATE (http://philcryer.com/projects/blackgate/) - it's new, it's cool, help us make it better/more useful for worldwide privacy for all internet citizens




#### Use better passwords





	
  * use ones that not only are hard to guess, ones that are impossible to remember! (go over my new way of choosing/using passwords)

	
  * I hope my ideas on passwords will be considered, and challenged if others have a better method - something needs to be done, this is still a disaster waiting to happen (over and over again)




## Conclusion(s)


If this stuff concerns you - Get involved, speak out, use tools/techniques outlined here, tell others, make noise about it! And now, the 2nd annual handout of Tootise Pops at the front of the stage, always a great ice breaker!

[![Tootsie Pops!](http://fak3r.com/wp-content/blogs.dir/12/files/tootsie_pops.jpg)](http://fak3r.com/2012/03/27/def-con-20-cfp/tootsie_pops/)
