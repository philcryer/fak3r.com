---
title: "HOWTO: burn an iso file from the command-line"
slug: "howto_burn_an_iso_file_from_the_command-line"
date: "2008-07-07T12:34:34-06:00"
author: "fak3r"
categories:
- blah
---

Put this in the 'post it here so I won't forget it' section, here I show how to use cdrecord to burn an ISO from the commandline.  When I download an ISO I hate having to open the cdburning program and get everything configured to burn, I'd rather shoot off a one liner, that's what this is all about.

    
    
    cdrecord -v speed=24 dev=/media/cdrom filename.iso
    


And for extra credit, I now have a shell script called burnit.sh that just contains the following:

    
    
    cdrecord -v speed=24 dev=/media/cdrom ${1}
    eject
    exit 0
    


To burn a downloaded ISO you just need to issue something like this:

    
    
    ./burnit.sh filename.iso
    
