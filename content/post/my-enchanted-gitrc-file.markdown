---
title: "My enhanced gitrc file"
slug: "my-enchanted-gitrc-file"
date: "2013-03-01T12:05:47-06:00"
author: "fak3r"
categories:
- bsd
- geek
- howto
- linux
tags:
- command-line
- commandline
- git
- gitconfig
- gitrc
---
At my last gig I got a crash course in using [git](http://git-scm.com/) fulltime, and I really enjoyed getting used to it, and leaving things like [subversion](http://subversion.tigris.org/) and [CVS](http://cvs.nongnu.org/) behind **forever**. And yes, while I'm sure I gave [Ant](http://ops.anthonygoddard.com/) a few gray hairs on the way, we eventually got there together. He gave me a customized .gitrc to use, which I've modified a bit, and now have shared as a gist. **This [.gitrc](https://gist.github.com/philcryer/5066010) tells you the branch you're on, with color handling of the bash prompt according to the branch/status of the current git repo**. For example, the prompt would look like this: [~/foo](master) $ with master colored RED, until you've added a file to the repo, but not committed it, when it will turn YELLOW. Once pushed it will turn back to RED to tell you things are in sync. As a bonus, after every command this will set the color of dollar prompt based on return value of previous command. If it's 0 it will be white to indicate success, if it's !0 it will be red, indicating an error. 
<!-- more -->

{% gist 5066010 %}


Install it by downloading the file, then in a console:

    
    mv gitrc ~/.gitrc


Hope this helps in your git adventures. Of course there are more tricks, shortcuts and cool things you can do in .gitrc, have you done anything that would expand on this? If so, share below! Thanks.
