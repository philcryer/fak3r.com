---
title: "HOWTO: create a pidfile for a startup script"
slug: "howto-create-a-pidfile-for-a-startup-script"
date: "2007-11-08T15:32:44-06:00"
author: "fak3r"
categories:
- howto
tags:
- code
- hacker
---

On the [monit](http://www.tildeslash.com/monit/) mailing list today someone asked how they could monitor a process that didn't have a pidfile associated with it.  Without thinking I jotted this down, there's likely a better way, but this should work and may be all I need for some init.d scripts for a couple of apps on ramon (the home server).  In the the beginning of the startup script, define the PIDFILE with the path and the cmd followed by the pid suffix and then just dump the PID number from the ps output into it:

    
    export PIDFILE=/var/run/${1}.pid
    ps -fe | grep ${1} | head -n1 | cut -d" " -f 6 > ${PIDFILE}


Once this is done, monit can monitor it just like it monitors any other process with a PID.  Later, for a shutdown hook, nuke the PIDFILE on the way out.

    
    if [ -f ${PIDFILE} ]; then
    rm ${PIDFILE}
    fi
    ### rest of shutdown ###
    exit 0


I think that should do it, anyone see a problem with that / a better way?
