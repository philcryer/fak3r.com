---
title: Update Googledrive icons to match dark Yosemite
date: "2014-11-11T12:15:09-06:00"
categories:
- howto
- geek
tags:
- howto
- icons
- yosemite
---
I've been using Apple [OS X Yosemite](https://www.apple.com/osx/) since the first beta, and I've been very impressed with its stability, and slight UI updates. Once it went official, I immediately tried out the 'dark' theme (_System Preferences > General_, then check: _Use dark menu bar and Dock_) which gives you just that, a dark menubar (black actually) with white icons and writing. The cool thing is that in the past this is one thing you could never (officially) change in OS X, so now that they've given it a proper integration and UI testing, it looks nice and feels new. Since the dark theme in Yosemite is so new not all apps support it, so they might have an icon that doesn't stand out as well now that the background isn't light. This is certainly the case with [Google Drive](http://drive.google.com), which I've been trying out as a way to consolidate documents and files that don't need to be private. The screenshot tells the tale of the icon, and it isn't pretty:

![Googledrive menu icon on Yosemite's dark theme (it's the 3rd from the left)](/2014/googledrive-yosemite-icon-before.png)
<!--more-->
After digging online I found a solution to the issue from an unrelated answer on Googledrive's forum, [Menu for menubar icon will not show on Mac OSX Yosemite Beta](https://productforums.google.com/forum/#!topic/drive/mtc7KLJLK94). It proposed grabbing the default icons and inverting the colors (from black to white) via Photoshop or a similar application. Luckily someone had already converted them and uploaded them to share. The zip included here includes all regular and [https://www.apple.com/search/?q=retina](retina) versions of the Google Drive menu icons, so all you need to do is copy them over the dark icons, restart Googledrive and you're ready to go.

### Update Googledrive icons to go with the dark Yosemite theme

* Download the [Googledrive Yosemite White Icons](/2014/googledrive-yosemite-white-icons.zip)
* In the commandline, assuming you downloaded the icons' zipfile to ~/Downloads, unpack the zipfile

```bash
cd Downloads
unzip googledrive-yosemite-white-icons.zip
```

* Change to the Contents directory of Googledrive

```bash
cd /Applications/Google\ Drive.app/Contents
```

* Make a backup of the current Resources directory

**NOTE** _when I’m going to modify something that I didn’t install I’ll make a copy of it and call it $original_name-dist so it’s clear what was installed by an installer versus what I messed up^H^H^H^H^H... I mean changed!_

```bash
sudo cp -R Resources Resources-dist
```

* Copy in the Googledrive white icons

```bash
sudo cp -R ~/Downloads/googledrive-yosemite-white-icons/*.png Resources
```

Now quit Googledrive and restart it. How does it look? You should see something like this:

![Our fixed Googledrive menu icon on Yosemite's dark theme](/2014/googledrive-yosemite-icon-after.png)
Of course if you ever switch back to the default light Yosemite theme you'll need your dark Googledrive icons back in play, and that's just as easy to do, you just copy the files in from the backed up Resources-dist directory.

```bash
cd /Applications/Google\ Drive.app/Contents
sudo cp -R Resources-dist/*.png Resources
```

And then stop/start Googledrive again.

How did it work? Are there any other Googledrive hacks you've heard of we can try out?
