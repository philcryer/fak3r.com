---
title: "Automate Ansible Tower cli"
description: "This should work"
date: "2019-01-17T20:48:13-06:00"
Tags: ["ansible", "tower", "automate"]
Categories: ["howto"]
---

I've used [Ansible](https://www.ansible.com/) for years, but only started using their web-front end, [Tower](https://www.ansible.com/products/tower) more recently. (Note, the upstream code was open-sourced last year, and it's worth checking out, they call it [AWX](https://github.com/ansible/awx)) So while I'm never a big fan of UI tools, Tower has proven quite helpful in allowing other teams access to deploying to their own dev servers from my Ansible code. There also have a cli tool to control Tower called (suprise) [tower-cli](https://github.com/ansible/tower-cli) that allows you to interact with Tower's API. While it's helpful, it's lacking a few features, so I worked up a wrapper script to handle my workflow. Here's the gist, with docs on how to install tower-cli, and run the tool. Give me some feedback if you have questions, of find it helpful.

<script src="https://gist.github.com/philcryer/fdce90d0b06517a49ff2fdba41b579df.js"></script>
