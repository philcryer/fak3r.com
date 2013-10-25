---
author: phil
comments: true
date: 2008-09-04 18:48:38
layout: post
slug: howto-webserver-in-100-lines-of-bash
title: 'HOWTO: webserver in 100 lines of Bash'
wordpress_id: 955
categories:
- geek
- howto
tags:
- bash
- code
- hack
- howto
- netcat
- networking
- perl
- system
- webserver
- www
---

[![](http://www.fak3r.com/wp-content/uploads/2008/03/opensource.png)](http://www.fak3r.com/wp-content/uploads/2008/03/opensource.png)I'm a big [Bash](http://netcat.sourceforge.net/) fan, I know [Perl](http://netcat.sourceforge.net/) is the more popular scripting language, and I'm slowly using it more, but hey, if I need something done, I can do it quicker in Bash (keeping in mind that I'm a systems guy, not a dev guy).  While at work looking up Bash related syntax I came across a page describing [how to run a webserver with 100 lines of Bash](http://quake.wikidot.com/www-server-in-100-lines-bash-script).  It uses the old school GNU utility [Netcat](http://netcat.sourceforge.net/) (nc) for communication between the pipes, and just a ton of basic logic and functions to pass it on to the user.  It's one of those things I look at and can't believe it works, but it does.  Of course security is unknown, as is the original author, but I consider this a reference on how to do networking things in Bash; who knows what I'll use (parts) of it for.  If anyone has details on who originally wrote this I'm all ears.[sourcecode language='xml']#!/bin/bash

function debug {
local severity="$1"
shift
local message="$@"

echo -n "`date -u`"    1>&amp;2
echo -ne '\t'        1>&amp;2
echo -n "$severity"    1>&amp;2
echo -ne '\t'        1>&amp;2
echo "$message"        1>&amp;2
}

function fix_path {
echo -n "$1" | head -n 1 | sed 's|^[/.-]*||' | sed 's|/\.*|/|g'
}

function serve_dir {
local dir="`fix_path "$1"`"
if [ "$dir" = "" ]; then
dir="./"
fi
echo 'HTTP/1.1 200 OK'
echo 'Content-type: text/html;charset=UTF-8'
echo
echo LISTING "$dir"
echo '  
'
ls -p "$dir" | sed -e 's|^\(.*\)$|\1  
|'
}

function serve_file {
echo 'HTTP/1.1 200 OK'
echo 'Content-type: application/x-download-this'
echo
local file="`fix_path "$1"`"
debug INFO serving file "$file"
cat "$file"
}

function process {
local url="`gawk '{print $2}' | head -n 1`"
case "$url" in
*/)
debug INFO Processing "$url" as dir
serve_dir "$url"
break
;;
*)
debug INFO Processing "$url" as file
serve_file "$url"
;;
esac
}

function serve {
local port="$1"
local sin="$2"
local sout="$3"

while debug INFO Running nc; do

nc -l -p "$port"  "$sout" &amp;
pid="$!"

debug INFO Server PID: "$pid"

trap cleanup SIGINT
head -n 1 "$sout" | process > "$sin"
trap - SIGINT

debug INFO Killing nc

kill "$pid"
done

debug INFO Quiting server
}

function cleanup {
debug INFO Caught signal, quitting...
rm -Rf "$tmp_dir"
exit
}

tmp_dir="`mktemp -d -t http_server.XXXXXXXXXX`"
sin="$tmp_dir"/in
sout="$tmp_dir"/out
pid=0
port="$1"

mkfifo "$sin"
mkfifo "$sout"

debug INFO Starting server on port "$port"
serve "$port" "$sin" "$sout"
cleanup[/sourcecode]
