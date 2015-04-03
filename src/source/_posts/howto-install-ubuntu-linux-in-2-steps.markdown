---
author: phil
comments: true
date: 2006-03-02 20:33:00
layout: post
slug: howto-install-ubuntu-linux-in-2-steps
title: 'HOWTO: Install Ubuntu Linux in 2 steps'
wordpress_id: 35
categories:
- linux
tags:
- howto
---

With full credit going to [The Central West End Linux Users Group (CWE-LUG)](http://www.cwelug.org/cgi-bin/wiki.cgi?Ubuntu_5.10), here’s a TWO STEP way to install a base Ubuntu Linux on a computer.

For a minimal install using the CD and a kickstart file on the net



	
  1. insert the Ubuntu 5.10 CD into the CD-ROM, then _choose either step 2 or 3_

	
  2. For a minimal/server install, at the boot prompt, type:






    
    <code>server ks=http://cwelug.org/~rwcitek/ubuntu/ks.cfg</code>








	
  1. Or, for a full/desktop install, replace “server” with “linux”:






    
    <code>linux ks=http://cwelug.org/~rwcitek/ubuntu/ks.cfg</code>



