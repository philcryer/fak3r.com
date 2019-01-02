---
title: "HOWTO: make old Firefox extensions install in 3.0 betas"
slug: "howto-make-old-firefox-extensions-install-in-30-betas"
date: "2008-03-06T23:14:06-06:00"
author: "fak3r"
categories:
- geek
- howto
---

If you're like me you're already running one of the Firefox 3 betas and loving the new features and stability lacking in the earlier series.  The only issue now is that it's taking extension writers time to update their extension to be compatible with the new Betas.  Some of these extensions can almost hold me back to the 2.x series since they're so useful, but 3 is just so much better in many ways.  So, to install an old (2.x) extension into a new (3.x) Firefox just takes a little tweak to the install file.  By default the extensions are hardcoded to something like 2.0.12 or the like, and *generally* nothing else needs to change to make it work with the 3.x series. (please re-read the *generally* part...done? ok).  To do this, just right click on the extension (I'll use the Gspace one for this example) and choose 'Save as...'  Once you have it, open up the commandline (or force WinZip to open it, unsure of how to do that) with unzip (the extensions end with .xpi, but are really a kind zipfile):

    
    unzip gspace-0.5.92-fx+fl.xpi


After it unpacks things, you'll have an install.rdf file in your current directory, it's just an XML structure file so you can open it in vi:

    
    vi install.rdf


Search for the following block for targetApplication and find the variable for maxVersion:

    
    2.0.0.*


Then simply modify the maxVersion variable to something like:

    
    3.*


Then save that file.  Next rebuild the xpi file with all the same files it had before, but this time with the modified install.rdf (NOTE: I renamed the file from .xpi to -edited.xpi so I could tell it apart)

    
    zip -r -D gspace-0.5.92-fx+fl-edited.xpi chrome defaults license.txt install.rdf gpl.txt chrome_jar.manifest chrome.manifest


And lastly open the newly created xpi with Firefox - it should now install no problem.  If there's an issue bail out by manually deleting the .jar (and any other chrome or default files you saw when you unpacked).  Worse case you'll need to build a new profile, but that's hardly a price to pay for the thrill of the adventure!  (blah, that sounds pretty geeky, eh?)
