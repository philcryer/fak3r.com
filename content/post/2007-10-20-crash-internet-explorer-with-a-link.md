---
title: "crash Internet Explorer with a link"
slug: "crash-internet-explorer-with-a-link"
date: "2007-10-20T19:09:51-06:00"
author: "fak3r"
categories:
- geek
tags:
- code
- hacker
---

I thought the days of crashing IE with just some malformed code were over, [apparently not](https://cryer.us/cgi-bin/nph.cgi/000110A/http/commandline.org.uk/more/microsoft/how-to-bring-down-internet-explorer-with-six-words-2007-08-07-19-18.html=3fshowcomments=3dyes). I just takes a misplaced wildcard in a style declaration to send it down.
`<style>*{position:relative}</style><table><input /></table>`
This took out IE on my work computer which is fully patched. I've read that people running IE under Wine in Linux have it crash as well, so it's certainly app dependant. For those of you playing at home, just [click here](https://cryer.us/cgi-bin/nph.cgi/000110A/http/fak3r.com/wp-content/uploads/2007/10/crash_ie.html) to try it for yourself. Extra credit if you actually save the file on your windows machine and then try to open it within Windows Explorer! Enjoy.
