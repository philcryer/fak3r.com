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

```
#!/bin/bash
#
# Set your bash prompt according to the branch/status of the current git repository.
#
# One-line install:
# curl https://gist.github.com/philcryer/5066010/raw/784e1e4c9df5289fe6f922fb6d122461d374758c/gitrc -o ~/.gitrc; echo "source ~/.gitrc" >> ~/.profile; source ~/.profile
#
# Originally forked from http://gist.github.com/31934 then modified liberally.
#
 
        RED="\[\033[0;31m\]"
     YELLOW="\[\033[0;33m\]"
      GREEN="\[\033[0;32m\]"
       BLUE="\[\033[0;34m\]"
  LIGHT_RED="\[\033[1;31m\]"
LIGHT_GREEN="\[\033[1;32m\]"
      WHITE="\[\033[1;37m\]"
 LIGHT_GRAY="\[\033[0;37m\]"
 COLOR_NONE="\[\e[0m\]"
 
function is_git_repository {
  git branch > /dev/null 2>&1
}
 
function parse_git_branch {
  # Only display git info if we're inside a git repository.
  is_git_repository || return 1
  
  # Capture the output of the "git status" command.
  git_status="$(git status 2> /dev/null)"
 
  # Set color based on clean/staged/dirty.
  if [[ ${git_status} =~ "working directory clean" ]]; then
    state="${GREEN}"
  elif [[ ${git_status} =~ "Changes to be committed" ]]; then
    state="${YELLOW}"
  else
    state="${RED}"
  fi
  
  # Set arrow icon based on status against remote.
  remote_pattern="# Your branch is (.*) of"
  if [[ ${git_status} =~ ${remote_pattern} ]]; then
    if [[ ${BASH_REMATCH[1]} == "ahead" ]]; then
      remote="↑"
    else
      remote="↓"
    fi
  fi
  diverge_pattern="# Your branch and (.*) have diverged"
  if [[ ${git_status} =~ ${diverge_pattern} ]]; then
    remote="↕"
  fi
  
  # Get the name of the branch.
  branch_pattern="^# On branch ([^${IFS}]*)"    
  if [[ ${git_status} =~ ${branch_pattern} ]]; then
    branch=${BASH_REMATCH[1]}
  fi
 
  # Display the prompt.
  echo "${state}(${branch})${remote}${COLOR_NONE} "
}
 
function prompt_symbol () {
  # Set color of dollar prompt based on return value of previous command.
  if test $1 -eq 0
  then
      echo "\$"
  else
      echo "${RED}\$${COLOR_NONE}"
  fi
}
 
function prompt_func () {
  last_return_value=$?
PS1="[\w]$(parse_git_branch)$(prompt_symbol $last_return_value) "
 
#temp
#PS1="[\w$(parse_git_branch)]$ "
}
 
PROMPT_COMMAND=prompt_func
```

Install it by downloading the file, then in a console:

```
mv gitrc ~/.gitrc
```

Hope this helps in your git adventures. Of course there are more tricks, shortcuts and cool things you can do in .gitrc, have you done anything that would expand on this? If so, share below! Thanks.
