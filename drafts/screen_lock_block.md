Open Powerpoint, and create a new presentation.

At the top, click on `Slide Show` then click on `Set Up Slide Show`

01

Now click the radio button next to `Browsed by an individual (window)`

02

Hit 'OK', then 'File > Save As… I left it as Presentation1.pptx and saved it to my Documents directory.

Open Powershell, `vi a new file (or just use `Notepad.exe if you're a Windows person), and name it `screen_lock_block.ps1`. Enter the following:

```
$pptx = "C:\Users\$ENV:username\Documents\Presentation1.pptx"
$application = New-Object -ComObject powerpoint.application
$presentation = $application.Presentations.open($pptx)
$application.visible = "msoTrue"
$presentation.SlideShowSettings.Run()
```

Save the file

. Chmod 755 theshow.ps1

run
