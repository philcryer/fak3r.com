---
title: "Screen Lock Block"
date: "2022-05-26T06:21:12-05:00"
Tags: ["workaround", "howto"]
Categories: ["security"] 
---
I work in a lot of different environments, and often (always?) the corporate "security" settings on my work laptop are silly. From not being able to delete shortcuts on the desktops, to not changing wallpaper, most things are silly, but sometimes it really effects my productivity. Since I always have more than one system going on my desk, so I can freely look up info on my Linux desktop for example, the laptop locking the screensaver automatically is always annoying. Look, I get why this is needed if you're on site, and hey, when I did go to offices I'd be the one that would reset the browser's homepage or email the team from a co-workers computer if they left it unlocked are are AFK. That's all good fun, but that's not the case anymore, we're WFH and I work in a secure (enough) environment. While browsing Twitter I came across one of my favorite #infosec people, [AlyssaM_InfoSec](https://twitter.com/AlyssaM_InfoSec/) who posted this awesome "PRO TIP": 

![](/2022/screen_lock_block-00.png)

PRO TIP: Want to defeat the screen timeout but your IT team blocked you from changing it? Run a power point slide show in Windowed mode and just minimize it out of the way. Works on both Mac and Windows.

You're welcome.

Hit me up for more #infosec circumvention tips. 😈😈

Open Powerpoint, and create a new presentation.

At the top, click on `Slide Show` then click on `Set Up Slide Show`

![](/2022/screen_lock_block-01.png)

Now click the radio button next to `Browsed by an individual (window)`

![](/2022/screen_lock_block-02.png)

Hit 'OK', then 'File > Save As… I left it as Presentation1.pptx and saved it to my Documents directory, if you change that you'll just need to update the `$pptx` line below to reflect the correct path.

Open Powershell, `vi` a new file (or just use `Notepad.exe` if you're a Windows person), and name it `screen_lock_block.ps1`. Enter the following:

```
$pptx = "C:\Users\$ENV:username\Documents\Presentation1.pptx"
$application = New-Object -ComObject powerpoint.application
$presentation = $application.Presentations.open($pptx)
$application.visible = "msoTrue"
$presentation.SlideShowSettings.Run()
```

Save the file, then run it, either by double clicking on the icon, or in Powershell:

```
. screen_lock_block.ps1
```

I've dragged the icon to my Windows taskbar so I just have to click it to turn on the functionality.
