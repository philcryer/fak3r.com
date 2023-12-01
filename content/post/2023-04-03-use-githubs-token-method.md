---
title: "HOWTO use GitHub's token method"
summary: "GitHub have turned off ssh authentication, here's a new way to do it that is almost as easy"
date: "2023-04-03T05:34:11-05:00"
Tags: ["authentication", "howto"]
Categories: ["development"] 
---
<div align="right"><img src="colophon/github.png" alt="GitHub"></div>
**TLDR** GitHub has turned off ssh authentication, read the code below to setup a method almost as simple using their new token method.

I'm a longtime [GitHub](https://github.com/) user and fan. The push to make this kind of development, some called it git-flow, has just almost effortlessly led to corporations adopting the practice. My current employer has an onsite GitHub server, which makes life easy for me, I don't want to use anything else, unless I needed a fully FOSS (Free Open Source Software) solution, but for now, I'm still a GH user. Recently though they changed their authentication and deprecated the ssh-key auth I've used for 10+ years. Now you need a token, a personal token, with 'fine grained control'. I don't mind this, in fact after working in companies and dealing with their crummy proxies, it makes sense to only go with https auth and not ssh, since it's usually blocked. So when I was updated this site earlier this week I got the message that I couldn't auth the way I used to, but it had a 'fine manual' in the form of some [documentation](https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/creating-a-personal-access-token). This is all fine and good, but getting it to work just like I had things working before was a chore. There's lots of posts about installing helpers, and other hacks, and I just didn't want to do that, again, my personal requirements are pretty simple, and I'm happy to make it as secure as I want. After searching around I found the soltion for me (at least for now):

* Login to [GitHub](https://github.com)
* Click on your avatar > `Settings` 
* Bottom of the left side click `Developer Settings`
* Click on `Personal access token` > `Fine-grained tokens`

Once that's completed, on your host:

Create a new file `~/.netrc`

Add the following contents:

```
machine github.com login <login-id> password <token-password>
```

Lock that file down so only you can read it:

```
chmod 0600 ~/.netrc
```

So plugin your GitHub username and your newly created GitHub token to that file. That's it, now it "just works" as it did before for ssh authentication! All credit goes to the poster [buddemat's post](https://stackoverflow.com/revisions/68558789/2) on Stackoverflow!

