---
title: "HOWTO: have vim create backup and tmp directories"
slug: "howto-have-vim-create-backup-and-tmp-directories"
date: "2007-01-16T11:54:21-06:00"
author: "fak3r"
categories:
- geek
- howto
- linux
tags:
- bsd
- howto
---

![vim](http://fak3r.com/wp-content/uploads/2007/01/vim_editor.gif)This may only apply to those of us geeks that use [vim](http://www.vim.org/) to admin servers daily, but today I needed a way to backup, and automate the creation of backup and tmp directories to house those ever annoying `~` and `.swp` files from showing up in my working directory (`$PWD`).  I didn't want to lose them, just move them somewhere so they don't clutter up the directory I'm working in.   The solution was a function I [found on the vim forums](http://www.vim.org/tips/tip.php?tip_id=1393).  Basically  it uses directories it creates in your home directory, so you'll have something like `~/.vim/backup` and `~/.vim/tmp` which is perfect; files are moved out of the way, but still backed up in a place you can rely on. I slightly reworked this, you can try it out by opening your `~/.vimrc` file, and find the line:

    
    <code>set backup            " keep a backup file</code>


Then after that cut/paste the following (if you don't have the set backup line, add it first):

    
    <code>function InitBackupDir()
    let separator = "."
    let parent = $HOME .'/' . separator . 'vim/'
    let backup = parent . 'backup/'
    let tmp    = parent . 'tmp/'
    if exists("*mkdir")
    if !isdirectory(parent)
    call mkdir(parent)
    endif
    if !isdirectory(backup)
    call mkdir(backup)
    endif
    if !isdirectory(tmp)
    call mkdir(tmp)
    endif</code>



    
    <code>endif
    let missing_dir = 0
    if isdirectory(tmp)
    execute 'set backupdir=' . escape(backup, " ") . "/,."
    else
    let missing_dir = 1
    endif
    if isdirectory(backup)
    execute 'set directory=' . escape(tmp, " ") . "/,."
    else
    let missing_dir = 1
    endif
    if missing_dir
    echo "Warning: Unable to create backup directories: " </code>



    
    <code>. backup ." and " . tmp
    echo "Try: mkdir -p " . backup</code>



    
    <code>echo "and: mkdir -p " . tmp
    set backupdir=.                 </code>



    
    <code>set directory=.
    endif</code>



    
    <code>endfunction          </code>



    
    <code>call InitBackupDir()
    </code>
