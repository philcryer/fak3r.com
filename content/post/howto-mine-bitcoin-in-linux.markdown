---
title: "HOWTO mine Bitcoin in Linux"
date: "2013-12-08T18:10:00-06:00"
categories:
- howto
- geek
- bitcoin
---
I started mining <a href="http://bitcoin.org/">Bitcoin</a> back in August, and have had modest success (more in a later post), but when I was getting started I couldn't find a good/current HOWTO; this post aimes to fix that. As usual these directions are for <a href="http://www.debian.org/">Debian GNU Linux</a>, but should work identically in <a href="http://www.ubuntu.com/">Ubuntu</a>, and in other Linuxes with the correct names for the needed packages. So, with that out of the way, let's get started!

First we need to install a few things, most you may already have these if you've compiled stuff before

<pre>apt-get install autoconf gcc make git libcurl4-openssl-dev libncurses5-dev libtool libjansson-dev libudev-dev libusb-1.0-0-dev</pre>

Now let's get a miner that can mine Bitcoin. While there are a few good projects out there, I use and recommend <a href="https://github.com/ckolivas/cgminer">cgminer</a> which is an _ASIC and FPGA miner in c for bitcoin_. Let's get the code

<pre>git clone https://github.com/ckolivas/cgminer.git</pre>

Next we need to compile it, and this step might change for you, depending on what kind of hardware miner you're using. Since I'm using the ever popular <a href="http://www.amazon.com/ASICMiner-Block-Erupter-USB-Sapphire/dp/B00CUJT7TO">ASICMiner Block Erupters</a>, I choose the compile option _icarus_ to use those chips

<pre>cd cgminer
./autogen.sh --enable-icarus</pre>

And then to compile

<pre>make</pre>

And that's it, yes you could run ``(sudo) make install`` to install it system wide, but I don't do that, I just run it from my home directory. This way it's easy for me to checkout the latest code, recompile and run it again (see script at the end). So, the last thing to do before running the cgminer is to join a mining 'pool' online, and getting the credentials for that. I won't go into a long 'here's how bitcoin mining works' (this time), but I'll just say mining on your own is never going to work with today's speeds, so by joining a mining pool your processing power is put to better use, and you get credit for the work your miner contributes. Before joining a pool do some <a href="https://en.bitcoin.it/wiki/Comparison_of_mining_pools">research online</a> to understand what you're getting into. Once you've joined you'll need to login to the web service and create a _worker_ for them to assign jobs to. Once that's complete you're ready to run things, so to (finally) get the game started, do something like this:

<pre>./cgminer --url http://URL_OF_YOUR_POOL:PORT_OF_YOUR_POOL -O YOUR-USERNAME_WORKER-NAME:WORKER-PASS</pre>

For example, I rock something like this:

<pre>./cgminer --url http://mint.bitminter.com:8332 -O johnny_worker1:sekretpa55word</pre>

Watch what the screen tells you and adjust as needed. Now for a script to wrap all of this up:

<pre>
#!/bin/bash
#
# define your pool details
poolurl=""
poolport="8332"
pooluser=""
poolworker=""
poolpass=""

if [ ! -d "cgminer" ]; then
    git clone https://github.com/ckolivas/cgminer.git
fi

cd cgminer
git pull
./autogen.sh --enable-icarus
make

./cgminer --url http://$poolurl:$poolport -O $pooluser_$poolworker:$poolpass
</pre>

Ok, now you should be rolling and now I owe you a followup post talking about Bitcoin exchanges, wallets, online transactions (and even cashing out) so you can actually use your Bitcoins. Until then, have fun!
