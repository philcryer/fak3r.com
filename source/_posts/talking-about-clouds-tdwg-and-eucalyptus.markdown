---
author: phil
comments: true
date: 2009-12-04 12:28:15
layout: post
slug: talking-about-clouds-tdwg-and-eucalyptus
title: Talking about clouds, TDWG and Eucalyptus
wordpress_id: 1908
categories:
- blah
---

![Glider - ESR's hacker emblem](http://fak3r.com/wp-content/uploads/2008/10/140px-glidersvg1.png)


We had a alternate (un-official) cloud talk at TDWG. Organized here [http://bit.ly/8LGUCr](http://bit.ly/8LGUCr) - one of the main things we wanted to cover, is to review what data is available now (or should be) out on Amazon's free public data sets: [http://developer.amazonwebservices.com/connect/kbcategory.jspa?categoryID=243](http://developer.amazonwebservices.com/connect/kbcategory.jspa?categoryID=243) From there we derived a software stack from ideas of what would be useful for biodiversity folks to have on an EC2 compatible Debian Linux instance to do distributed computing against those sets. [http://bit.ly/8GSEa7](http://bit.ly/8GSEa7) This in turn builds off of what has already been done with BioLinux [http://www.jcvi.org/cms/research/projects/jcvi-cloud-biolinux/](http://www.jcvi.org/cms/research/projects/jcvi-cloud-biolinux/) which is more of a desktop-able EC2/Eucalyptus image. Eucalyptus ([http://open.eucalyptus.com/](http://open.eucalyptus.com/)) is an open source project for you to bring up your own 'private clouds' that leave open the ability to migrate part of all of it to Amazon's EC2 instances if you needed more power.<!-- more --> But you know me, my idea would be to have a data base of participating parters worldwide that you could aim at, and if they were up/available, you could use their remote services to crank your data instead of using Amazon and being charged. I think this way scientists would really get to experiment with different data, without having to worry about cost. Of course enough institutions or partners would have to participate (and this doesn't take into account any politics), but failing that, the Euca setup would at least provide a proof of concept that could be ramped up just by moving things to EC2.

Lastly, we now have a code site where we want to house different ways of accessing this data, and being able to send jobs out to EC2/Euca, here: [http://code.google.com/p/biodivertido/](http://code.google.com/p/biodivertido/)

I'm working with Eucalyptus learning how to set it up, and then configure a slim Linux image that could be scaled out. From there, add the useful applications to it, make it a template others could use on their own Euca setups, or EC2, or both, to do map/reduce, or whatever work they want. This is where my expertise ends, I just want to facilitate the community to be able to get to that point. But, to address that point - I sent an email out to the group:


> "All -- Nick posted this to Twitter, but I wanted to highlight it for everyone here [http://www.politigenomics.com/2009/11/bioinformatics-and-cloud-computing.html](http://www.politigenomics.com/2009/11/bioinformatics-and-cloud-computing.html)

that was prompted by this blog post by David Dooling (Genome Project) [http://www.warelab.org/blog/?p=307](http://www.warelab.org/blog/?p=307)

The Genome Project is at Washington University, here in Saint Louis, where an in-law of mine recently started working. So, I have an in there, and have been trying to find time and questions to present to people on the project for ideas/reviews of our approaches."


I would suggest we work with people who have already done this on our level (Tim from Gbif) then come up with real world examples that we could test out, and then draw in more experienced folks like this to comment on how we could best use 'the cloud' be it local/private/shared/ec2/etc for the biodiversity community.
