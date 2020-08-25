---
title: "Randomize your Twitter avatar from the commandline"
date: "2020-08-23T17:44:18-06:00"
Tags: ["twitter", "python", "avatar"]
Categories: ["howto"]
---

I'm pretty active on [Twitter](https://twitter.com/fak3r), and I wanted a way to change my Twitter avatar, to a randomly 'glitched' out version, on a set time (say hourly). I figured out a way to do this using Python, Twitter's API, and some random glitch services online. This method requires that you manually glitch the images you want, drop them in a directory, create a new Twitter App to get permissions to change your avatar, then install and use [Tweepy](https://www.tweepy.org/) to do the heavy lifting, all called by cron. I called the project [randota](https://github.com/philcryer/randota.git) = randomize twitter avatar = randomize your Twitter avatar from the commandline.

<img src="https://raw.githubusercontent.com/philcryer/randota/master/img/me.jpg" heigh=150 width=150> &nbsp;&nbsp;&nbsp;
<img src="https://raw.githubusercontent.com/philcryer/randota/master/img/me-glitched-11-23-2019-3-55-53-PM.png" height=150 width=150> &nbsp;&nbsp;&nbsp;
<img src="https://raw.githubusercontent.com/philcryer/randota/master/img/me-glitched-11-23-2019-3-54-18-PM.png" height=150 width=150> &nbsp;&nbsp;&nbsp;
<img src="https://raw.githubusercontent.com/philcryer/randota/master/img/me-glitched-11-23-2019-3-53-48-PM.png" height=150 width=150>

Follow the steps to do this for your own Twitter avatar.

<!--more-->

# Install

## Get the code

Clone the git repo and start to configure your environment

```
git clone https://github.com/philcryer/randota.git
cd randota
rm -rf img/*
cp config.json.dist config.json
```

## Create a Twitter app

The first step you should take is to create a new Twitter App so you can get permissions to update your user's avatar. While there are many howtos out there, this one is complete and should get you going; [How to create a Twitter application](https://docs.inboundnow.com/guide/create-twitter-application/)

Once you get to "8. Make a note of your OAuth Settings", be sure and save the Consumer Key, Consumer secret, Access token and Access token secret, defining each of the values in your newly created `config.json` file

## Glitch your avatar

To glitch your avatar, play on here, otherwise if you just want to use standard avatars and rotate between them, jump to the next section

1) save your avatar locally
2) hit [jpg-glitch](https://snorpey.github.io/jpg-glitch/), upload your avatar, glitch it as much as you want, or just choose the 'random' option
3) save the file into a directory `img`
4) repeat as many times as you'd like to increase your randomness

## Install requirements

```
sudo python -m pip install -r requirements.txt
```

## Run it

```
python randota.py
```

View the hilarity on Twitter.com/<your_username>

## Automate it

Add a new line to your user's crontab, fill out the path to where your code is

```
0 * * * *    python ${HOME}/code/randota/randota.py >/dev/null 2>&1
```

## What's next?

So when I started this I wanted it to just randomize my avatar for some variety and for fun, but what else could we do? Obviously it could be more active, for example, your hockey team wins, maybe it puts up an icon of your team (much like the [Cub's W flag](https://en.wikipedia.org/wiki/Cubs_Win_Flag)). Maybe it shows an umbrella if it's raining, mabye something related to the news of the day. I've also thought of having it change your displayed name in Twitter, again, responding to some action in the "real world" that changes something on your account. Think of something cooler to do than just this and please fork, make a PR, or share your thoughts. Thanks!
