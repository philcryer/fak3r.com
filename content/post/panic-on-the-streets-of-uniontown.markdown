---
title: "Panic on the streets of Uniontown!"
slug: "panic-on-the-streets-of-uniontown"
date: "2006-01-06T18:17:00-06:00"
author: "fak3r"
categories:
- humor
tags:
- hacker
- security
---

Here’s another example of people being afraid of what they don’t know about; [Student accused of trying to crash school’s computer system](http://www.wkyc.com/akron/akron_article.aspx?storyid=45721).  ”_A Stark County high school senior has been arrested and charged for allegedly trying to crash his school’s computer system. Police say the student, created a website which connected to the school’s system. When enough users logged on and hit the F5 button, it overloaded the school’s system. But, Lake High School caught-on before the system crashed. Its computers started slowing down. “It’s a crime and it is important we take this seriously … especially for school officials … it could have done a tremendous amount of damage,” said Canton City Prosecutor Frank Fronchione. Stone is charged with a felony and could face jail time. But prosecutors say community service is more likely and disciplinary action from the school_.”  While this is mischief, I can’t believe they’re going to change him with a felony?  Define ‘tremendous amount of damage’, what’s the worst thing that could have happened?  Some server would lockup and need to be rebooted?  Goddamn, remind me to teach my kids how to hide their tracks when they’re learning how computers and networking works.  Here’s the [video](http://www.wkyc.com/video/player.aspx?aid=18650&sid=45721&bw=) of the ‘news’ story, including some great ‘the sky is falling’ comments from school administrators.  In doing my own investigative journalism, I hit the [schools’ site](http://lake.stark.k12.oh.us/) [[Google cache](http://64.233.167.104/search?q=cache:fibEIpY6TtgJ:lake.stark.k12.oh.us/+&hl=en)] (NO I DIDN’T RELOAD THE PAGE! DON’T PANIC!) where I saw a bunch of MySQL (database) connect errors, and an ‘About’ page that contained ‘Edit Content Here’ - so it’s not the most robust or thought out site.  Entering a dummy URL gave up that the website runs Apache on a OS X server box.  I don’t know who’s running the shop there, but if they can’t control server load because of some kid and his friends are pressing F5 too many times, they’re the ones who should be charged (although with that lack of skillz displayed I’d imagine their site getting owned is more likely).  I’m just surprised someone didn’t throw in the ‘terrorist’ designation for more knee jerk fun.
