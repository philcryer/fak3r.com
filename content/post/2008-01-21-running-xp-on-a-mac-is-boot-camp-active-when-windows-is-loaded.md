---
title: "Running Windows XP on a Mac"
slug: "running-xp-on-a-mac-is-boot-camp-active-when-windows-is-loaded"
date: "2008-01-21T10:36:48-06:00"
author: "fak3r"
categories:
- geek
- rant
tags:
- apple
- bootcamp
- mac
- support
- windows
---

:


> Boot Camp simplifies Windows installation on an Intel-based Mac by providing a simple graphical step-by-step assistant application to dynamically create a second partition on the hard drive for Windows, to burn a CD with all the necessary Windows drivers, and to install Windows from a Windows XP installation CD. After installation is complete, users can choose to run either Mac OS X or Windows when they restart their computer.<!-- more -->


(Support) states that, "_Using bootcamp to run Windows images with Apple is not a supported platform, nor is any emulated system_", but it my understanding that neither of those statements are true.  If I am correct, I would look at 1) the hardware on the Mac, specifically the network card, for trouble, or 2) the drivers Boot Camp installed, if they are not native Windows drivers, which I suspect they must be some variant of, since they load under Windows.  Perhaps if the user could provide the model of the Mac and the network card model/ID he sees in Device Manager under Windows it could be checked against (company) to see if they support that within a Windows install.  From this point I would think this would be the same as troubleshooting any other Windows driver issue.  Having said that I've never done this, although I've always thought it would be a compelling solution for many.  If (company) doesn't want to support Apple I could, begrudgingly, understand that, however I'd like to see them make that claim instead of blaming some software that isn't in play.

I don't want to ruffle any feathers with (company) by telling them what to support, I'm more interested in the real issue, which I don't think is Windows/Mac related, and a working solution.  If I'm missing something let me know, but my understanding is that after the EFI (Extensible Firmware Interface Intel's BIOS  replacement) bootstraps the system that Windows completely takes over after it's selected, and natively runs crummy the way it does on any other x86 based hardware.
