---
title: "Screen Lock Block"
summary: "Defeat the screen timeout but your IT team blocked you from changing"
date: "2022-05-26T06:21:12-05:00"
Tags: ["workaround", "howto"]
Categories: ["security"] 
---
I work in a lot of different environments, and often (always?) the corporate "security" settings on my work laptop are silly. From not being able to delete shortcuts on the desktops, to not changing wallpaper, most things are silly, but sometimes it really effects my productivity. Since I always have more than one system going on my desk, so I can freely look up info on my Linux desktop for example, the laptop locking the screensaver automatically is always annoying. Look, I get why this is needed if you're on site, and hey, when I did go to offices I'd be the one that would reset the browser's homepage or email the team from a co-workers computer if they left it unlocked are are AFK. That's all good fun, but that's not the case anymore, we're WFH and I work in a secure (enough) environment. While browsing Twitter I came across one of my favorite #infosec people, [AlyssaM_InfoSec](https://twitter.com/AlyssaM_InfoSec/) who posted this awesome "PRO TIP": 

![](/2022/screen_lock_block-00.png)

This is a complete game changer for me, and has helped so much. I can multi-task across multiple systems, I can follow meetings while I'm eating, plenty of other things. As with everything I do, my next question was, how can I automate this? Since I wanted a way to do it that I could share with others that might not be as technical I wanted to do it the "Windows way" and use [PowerShell](https://docs.microsoft.com/en-us/powershell/), which I'd never done before, and write a script. Here's how I did it, and how you can too, all you need is a Windows desktop and have [PowerPoint](https://docs.microsoft.com/en-us/office/client-developer/powerpoint-home) installed.

## HOWTO

Let's setup a new PowerPoint presentation and configure it to do what we need. 

* Open Powerpoint, and create a new presentation.
* At the top, click on 'Slide Show' then click on Set Up 'Slide Show'

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

## Summary and Acknowledgements
