---
title: "Graphene OS"
date: "2024-05-22T09:16:11-05:00"
Tags: ["android", "linux"]
Categories: ["privacy"] 
draft: false
---
<div align="center">
	<img src="/2024/me-and-graphene.jpg">
	<figcaption>It's alive! Graphene OS build 2024051600 booting on the Pixel 8a</figcaption>
</div>

## Summary

I'm running [Graphene OS](https://grapheneos.org/), the "private and secure mobile operating system with Android app compatibility. Developed as a non-profit open source project", on my new [Google Pixel 8a](https://store.google.com/product/pixel_8a)), and it's going great. Not only does this address many long term concerns about online privacy *and* security, but it allows me full control over how exposed I am to online marketing, and more nefarious things. While I seemingly hemmed and hawed over moving to running Graphene OS as my main "daily driver", it only took chatting with some users and developers on the project's [chat channels](https://grapheneos.org/contact#community) to tell me that there was nothing to fear. In fact, with their excellent [documentation](https://grapheneos.org/install/) I found installation to be far eaiser than when I used to run custom Android ROMs in the past (LineageOS, Paranoid Android, crDroid, etc).

## Pre-Install

Since Graphene OS only supports [Pixels phones](https://grapheneos.org/faq#supported-devices) (with reason), I bought a new Google [Pixel 8a (akita)](https://store.google.com/us/product/pixel_8a) and did a fresh install. While I found it kind of funny that you would buy a Google phone to rid yourself, or at least serverly control, of Google's access to your data, it makes sense. They build good, secure hardware that, while it is designed to snoop on you, can be instructed and limited in ways you can't do with other hardware.

## Post-install and configuration

In limited Google's access to your data, the OS fully allows you to install virtually any app you want and have it work, however sometimes you'll have to tweak some settings, but I actually appreciate this because it fully exposes how various applications are accessing your data. The OS runs a compatible Google Play service in a sandbox, which allows you to fully control the access it has. WHile I'll admit to having some hickups, any issues were quickly figured out by following the documentation or asking questions on their chat. An interesting example was the Southwest Airlines app, once installed would just freeze on the splashscreen. Simple tips to allow more permissions, or even toggling Graphene's [exploit protection compatiblity mode](https://discuss.grapheneos.org/d/8330-app-compatibility-with-grapheneos) allowed the app to fuction fully.

> fak3r — Yesterday at 10:15 PM
> Thank you! First suggestion fixed it Settings ➔ Apps ➔ App in question ➔ Exploit protection ➔ Native code debugging

Another issue was me wanting to try out [Android Auto](https://www.android.com/auto/) when I had a rental car that offerred that functionality. Installing the application directly from the Play Store (or the alternative Play Store front ends offered) is blocked, leading you to find [documentation on installing Graphene's own, locked down version](https://discuss.grapheneos.org/d/10094-android-auto-working-feature-set/7)). Once installed and configured, it worked perfectly. 

## Conclusion

Graphene OS is the best Android ROM I've ever installed and it has been my daily driver now for over two months. While some applications may require some tweaking to get functioning as needed, this helps educate the user on how their data is used, while providing a real way forward to having the app's originaly functionality. Yes it'd be amazing if you could avoid installing *any* Google software; and it is possible, but you're serverly limited to using most popular applications, this is the next best thing, allowing the user full control (and again, understanding) of their personal data. Highly recommended.
