+++
title = "Quickly Setup a Decent Vimrc"
Description = "On new servers, we need a sane start to our vim setup"
date = 2018-08-16T18:22:58-05:00
Tags = ["Development"]
Categories = ["Development", "vim", "vimrc"]
menu = "main"
+++

I'm on new servers pretty often, and usually their [vim](https://www.vim.org/) configuration is quite lacking, I mean, come on `/etc/skel` can only get you so far these days.. While I have my `.vimrc` out there, copying it down fails because I don't have the right version of vim installed, plus I'm missing plugins and other goofy stuff I call out that should be in my `$HOME/.vim` directory. So, here's a start to get me rolling on a new server with a decent, basic vimrc setup so I can get to work. I'll update this as I go, and once I have it ready I'll redo my deploy scripts to run [Ansible](https://www.ansible.com/) to properly "get the game started" ([Slackware](http://www.slackware.com/) reference for my old timers out there!). Until then, this will do:

``` 
#!/bin/sh

# in Debian based GNU/Linux distros (Debian, Ubuntu, Kali...)
# TODO make a conditional to cover other distros
sudo apt install vim

git clone --depth=1 https://github.com/amix/vimrc.git ~/.vim_runtime

sh ~/.vim_runtime/install_basic_vimrc.sh

exit 0
```
