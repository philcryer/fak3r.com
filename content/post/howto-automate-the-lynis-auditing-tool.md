+++
featuredalt = ""
categories = [ "howto", "security" ]
tags = ["howto", "gist", "lynis" ]
description = ""
title = "HOWTO Automate the Lynis auditing tool"
featured = ""
featuredpath = ""
author = "fak3r"
date = "2017-10-19T17:18:31-06:00"

+++
Often when working with a client I'll have recommendations on server settings and configurations, but sometimes things are not obvious, and I want another set of eyes to audit security settings. External scanners are fine but are mostly relegated to be run by the security teams, so using an open source auditing tool can help with security auditing, hardening, and compliance while helping to determine if you have things configured and setup optimally. Enter [Lynis](https://cisofy.com/lynis/), "_an open source security auditing tool Linux, macOS, and Unix systems. Used by system administrators, security professionals, and auditors, to evaluate the security defenses of their Linux and UNIX-based systems. It runs on the host itself, so it performs more extensive security scans than vulnerability scanners._" Sounds good, to make this easier to run I've written a simple script to pull the latest version of the scanner, unpack it and run it with the general settings. It's helpful, give it a try and see what it sees on your system.

<script src="https://gist.github.com/philcryer/c999d0d77e242a72595b0657e692ed50.js"></script>
