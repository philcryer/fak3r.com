---
title: "HOWTO fix a Nintendo 3DS with broken wifi"
slug: "howto_fix_a_nintendo_3ds_with_broken_wifi"
date: "2013-03-27T22:40:38-06:00"
author: "fak3r"
categories:
- geek
- howto
tags:
- 3ds
- error
- nintendo
- wi-fi
- wifi
---

<div align="center">
![Nintendo 3DS](https://fak3r.com/2013/1248580_flame_red_n3ds_final-300x256.jpg)
</div>
<br>So my son's [Nintendo 3DS](https://www.nintendo.com/3ds) wi-fi stopped working a few weeks ago, and I finally had time to take a look at it to see if there was anything that could be done. The situation was that it would work fine playing games or doing anything not needing wi-fi access, but as soon as you'd turn on the wi-fi switch, after about 10 seconds, the screen would go black and display the message: 
<ul>Error Message: Error Has Occurred. Hold down the POWER Button to turn the power off.</ul>
Following the directions and restarting changed nothing, after 10 seconds or so with wi-fi enabled, blamo, black screen of death! Nintendo's troubleshooting guide led me to [the exact issue](https://www.nintendo.com/consumer/systems/3ds/en_na/ts_system.jsp), but the recommended course of action was only 
<ul>"Hold down the POWER button to power off the system, Continue to use the system to see if the error reoccurs, General system stability can be improved by applying the System Update that was made available on March 24th, 2011. Try applying this System Update and see if the issue improves."</ul>
The first two recommendations of course did nothing, and the last one was impossible since you need wi-fi to update the firmware! I followed their links to look into repair, but since the unit is out of warranty it would <b>cost $85.00 (not including tax and shipping) </b>for them to fix it. Hmm... seeing that these go for ~170$ brand new, that seemed pretty excessive, so I dove into a [Google search](https://encrypted.google.com/#hl=en&output=search&sclient=psy-ab&q=An+Error+Has+Occurred.+Hold+Down+the+POWER+Button) to see what I could find. Forum after forum I found that while this is a pretty common problem, no one seemed to have any idea how to fix it. Finally on ifixit.com I found [a promising answer](http://www.ifixit.com/Answers/View/75518/How+do+I+fix+my+3DS+Error+Problem#answer118768), and it turned out to work perfectly, thanks [Alejandro](http://www.ifixit.com/User/583708/Alejandro). <br><br><b>TL;DR</b> the wi-fi card works itself loose and only needs to be reseated for it to work again. Knowing this, I got down to business and started ripping this sucker apart.

<!-- more -->

To get started you'll need a [Philip #00](http://www.ifixit.com/Tools/Phillips-00-Screwdriver/IF145-006) screwdriver, which is the same size you need if you want to rip your Macbook Pro apart (look for a future post on that). Now, flip the 3DS upside down and remove the 4 silver screws across the top and take off the back cover. Now you'll find 8 black, recessed screws you'll need to take out, lastly take out the (really) small screw at the lip of the cartridge slot. Next carefully pry the bottom of the case off of the board, but be careful, once you have it open you'll see the (really) thin ribbons that connect the board to the left and right shoulder buttons, you don't want to break those. Finally locate the wi-fi card, which will look something like this:

<div align="center">
<img src="https://fak3r.com/2013/231194-1-300x219.jpg" border="0"><br>
</div>
The Nintendo 3DS wi-fi card<br>

<b>NOTE</b> my card was not next to the cartridge slot like these shots show, but was at the bottom, and it had a white sticker on it, but no big deal, you'll be able to tell what it is, it's not too complex once you get in there.

It was about this time my daughter came by to see what I was up to, I explained all the different parts and what did what, she just said, _"I sure hope you know what you're doing"_. Hey, when has that stopped me? So next, grab each of the long sides of the chip and slowly rock it back and forth while pulling up. As the original poster said, **don't try and pry it off**, make sure it comes off slowly.

<div align="center">
<img src="https://fak3r.com/2013/231421-2-300x220.jpg" border="0"><br>
</div>
The Nintendo 3DS with the wi-fi chip unplugged<br>

Now that you see the plug and how it operates, just re-attach it, making sure to use enough pressure to make sure it's **really** down. At this point I powered it up to make sure everything was working, and the wi-fi was working like a champ, so it was time to seal it back up. It was cool that it was so easy to fix, and it was fun digging into another piece of hardware; it only would have been better if we would have voided a warranty in the process! This wasn't [the first time](http://fak3r.com/2007/05/29/howto-fix-a-g3-ibook-with-a-bad-logic-board-for-26-cents/) I've had success taking apart hardware, and it won't be the last, since, if you can't open it, you don't own it.
