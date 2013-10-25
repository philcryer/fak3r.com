---
author: phil
comments: true
date: 2008-07-28 12:04:29
layout: post
slug: howto-recursively-download-only-specific-file-types
title: 'HOWTO: recursively download only specific file types'
wordpress_id: 838
categories:
- howto
- music
tags:
- download
- grab
- howto
- mp3
- snarf
- wget
---

Have you ever found a batch of mp3s online on someone's 'Index of' page?  I know you have (and if not, do a search for 'google hacks' in google to learn about the fun)  The issue always comes up that I find an album I want to grab, but the individual files are in a directory, so you have something like band_name-album_name/01-songone.mp3, and so on.  To grab all of them I used to issue a [wget](http://www.gnu.org/software/wget/) command, with the -r (recursive) switch like this:

    
    wget -r http://www.someurl.com/band_name*


but then I'd end up with a ton of other files from the root directory that would take time and confuse the download so I'd have to search around for the mp3 payload.  I found a better way to do it, still using the -r for recursive search, but then only downloading the mp3s, forgoing any html pages or other directories in the root.  The command goes something like this:

    
    wget -A mp3,mpg,mpeg,avi -r -l 3 http://www.someurl.com/band_name*


The `curl` command operates in a similar way. Its advantage is that it's actively developed. Other similar commands that you can use are `snarf`, `fget`, and `fetch`, but I don't see a direct advantage over `wget` with the proper filters.
