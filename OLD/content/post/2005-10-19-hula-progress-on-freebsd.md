---
title: "Hula progress on FreeBSD"
slug: "hula-progress-on-freebsd"
date: "2005-10-19T15:11:00-06:00"
author: "fak3r"
categories:
- General
tags:
- bsd
- code
---

I've been pretty quiet about [Hula](http://www.hula-project.org/Hula_Project) since I've been unable to successfully build *and* run it since r370 (currently Hula is at r609). While I've solved and committed all the autogen build issues on [FreeBSD](http://www.freebsd.org), it still won't run; the controlling hulamanager process just hangs, with no errors or output to help out. On the mailing list this behavior is reportedly due to the (hardlinked) renaming of 'server messaging server' to 'hula messaging' server, which bombs if you use the filesystem based mdb. Alex sent me this patch:




    
    <code>diff -urNad --exclude=CVS --exclude=.svn ./src/libs/mdb-file/mdbfile.c
    /tmp/dpep-work.Qpsn4d/hula-0.1.0+svn472/src/libs/mdb-file/mdbfile.c
    --- ./src/libs/mdb-file/mdbfile.c	2005-09-16 12:19:45.000000000 +0100
    +++ /tmp/dpep-work.Qpsn4d/hula-0.1.0+svn472/src/libs/mdb-file/mdbfile.c	2005-09-20 20:45:41.000000000 +0100
    
    @@ -3207,8 +3207,8 @@
    
    MDBFile.unload = FALSE;
    strcpy(MDBFile.localTree, "\Tree");
    -    strcpy(MDBFile.serverDN, "\Tree\Context\Hula");
    -    strcpy(MDBFile.replicaDN, "Hula");
    +    strcpy(MDBFile.serverDN, "\Tree\Context\Server");
    +    strcpy(MDBFile.replicaDN, "Server");
    strcpy(MDBFile.base64Chars, "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=");
    
    #if defined(LINUX)</code>





and it applies cleanly, but yet the same issue occurs.  I have a [bug written on this](https://bugzilla.novell.com/show_bug.cgi?id=121894), and will try to get back on IRC tomorrow and work on it.  Also, due to a request on the hula-dev list, I have added some logic to my [gethula script](http://cryer.us/hula/gethula/); version 1.7 should now support [NetBSD](http://www.netbsd.org/), but I'm waiting for feedback on that.  Again, it *should* compile, but I don't expect it to run...yet.
