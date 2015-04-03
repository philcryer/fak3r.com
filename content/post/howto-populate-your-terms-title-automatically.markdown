---
title: "HOWTO: populate your term's title automatically"
slug: "howto-populate-your-terms-title-automatically"
date: "2007-06-13T10:58:01-06:00"
author: "fak3r"
categories:
- geek
- howto
- linux
tags:
- bsd
- hacker
- howto
---

![g33k](http://fak3r.com/wp-content/uploads/2007/06/g33k.jpg)When you're running a ton of termial windows or tabs, it helps to have the title of the box name, along with some environment values, easily available to keep you orientated. Here's a quick script I created to do this automatically when called via your `.profile` file in your home directory.


    
    #!/bin/bash
    HOST_NAME=`hostname -f`
    if [ `id -u` = 0 ]; then
    OPT="`uname` (`uname -a | cut -f12 -d' ' -`) - ROOT USER"
    else
    OPT="`uname` (`uname -a | cut -f12 -d' ' -`)"
    fi
    REPLACE="${HOST_NAME} - ${OPT}"
    echo -n -e "33]0; $REPLACE 07 "
    echo "${REPLACE}"
    exit 0



When I run this script in my term here at work, the title or tab becomes:


    
    nldg-8 (Linux / x86_64)



Drop this into a `bin` directory your user can hit - I always put on in my home directory and append _~/bin_ to my PATH in my _.profile. _For Solaris fans/users, it needs to be done a bit differently:


    
    #!/usr/local/bin/bash
    HOST_NAME=`uname -a | cut -f2 -d' ' -`
    OPT="(`uname -a | cut -f1 -d' ' -` / `uname -a | cut -f6 -d' '`)"
    REPLACE="${HOST_NAME} - ${OPT}"
    echo -n -e "33]0; $REPLACE 07 "
    echo "${REPLACE}"
    exit 0



foo
