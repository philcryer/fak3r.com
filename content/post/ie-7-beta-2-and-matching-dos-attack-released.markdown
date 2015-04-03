---
title: "IE 7 Beta 2 (and matching DoS attack) released"
slug: "ie-7-beta-2-and-matching-dos-attack-released"
date: "2006-02-01T20:47:00-06:00"
author: "fak3r"
categories:
- General
tags:
- code
- hacker
- security
---

Amazing, so today Microsoft [releases Beta 2 of IE 7](http://www.microsoft.com/windows/ie/ie7/ie7betaredirect.mspx), and almost simultaneously comes a tailor made [DoS attack](http://www.security-protocols.com/advisory/sp-x23-advisory.txt)!  ”_Overview: A denial of service vulnerability exists within Microsoft Internet Explorer 7.0 Beta 2 which allows for an attacker to cause the browser to crash, and or to execute arbitrary code on the targeted host.  Technical Details: When running a specially crafted .html file, urlmon.dll inproperly parsers the ‘BGSOUND xsrc=file://—’ (approx. 344 dashes) and causes the crash. … Vendor Status: Microsoft was notified. Workaround: Mozilla Firefox_.”  If you are running IE 7 Beta 2 and want to give it a go, go to that link and construct the code, or simply [click here](http://www.security-protocols.com/poc/sp-x23.html) for the proof of concept.  I like how this comes up just after the zero day WMF flaw, and how it nicely dovetails into their “Trustworthy Computing” effort (emphasis on effort).  ”_Trustworthy Computing is a long-term, collaborative effort to provide more secure, private, and reliable computing experiences for everyone. This is a core company tenet at Microsoft and guides virtually everything we do. Trustworthy Computing is built on four pillars: Security, Privacy, and Reliability in our software, services, and products; and integrity in our Business Practices_.”  Sure, sounds like a game plan.
