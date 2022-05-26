---
title: "Screen Lock Block"
date: "2022-05-26T06:21:12-05:00"
Tags: ["workaround", "howto"]
Categories: ["security"] 
---


![](/2022/screen_lock_block-00.png)

https://twitter.com/AlyssaM_InfoSec/status/1527058059343941632

👑 Alyssa Miller 🦄 (Speaking @RSAConference)
@AlyssaM_InfoSec
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
