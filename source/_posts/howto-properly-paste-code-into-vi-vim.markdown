---
layout: post
title: "HOWTO properly paste code into vi/vim"
date: 2013-09-04 12:35
comments: true
categories: 
- geek
- howto
---
I'm sure you've done this before, you copy a big block of text or code and paste it into [vi/vim](http://www.vim.org/) (from now on referred to simply as vi). Instead of looking like you want it to, it takes every tab and just autoindents like crazy giving you a mess to clean up. So, for example, you copy the following

<pre>current_dir = File.dirname(__FILE__)
  user = ENV['OPSCODE_USER'] || ENV['USER']
  node_name                user
  client_key               "#{ENV['HOME']}/.chef/#{user}.pem"
  validation_client_name   "#{ENV['ORGNAME']}-validator"
  validation_key           "#{ENV['HOME']}/.chef/#{ENV['ORGNAME']}-validator.pem"
  chef_server_url          "https://api.opscode.com/organizations/#{ENV['ORGNAME']}"
  syntax_check_cache_path  "#{ENV['HOME']}/.chef/syntax_check_cache"
  cookbook_path            ["#{current_dir}/../cookbooks"]
  cookbook_copyright "Your Company, Inc."
  cookbook_license "apachev2"
  cookbook_email "cookbooks@yourcompany.com"</pre>

and paste it into vi, and it ends up looking like...<!--more-->

<pre>current_dir = File.dirname(__FILE__)
  user = ENV['OPSCODE_USER'] || ENV['USER']
    node_name                user
      client_key               "#{ENV['HOME']}/.chef/#{user}.pem"
        validation_client_name   "#{ENV['ORGNAME']}-validator"
          validation_key           "#{ENV['HOME']}/.chef/#{ENV['ORGNAME']}-validator.pem"
            chef_server_url          "https://api.opscode.com/organizations/#{ENV['ORGNAME']}"
              syntax_check_cache_path  "#{ENV['HOME']}/.chef/syntax_check_cache"
                cookbook_path            ["#{current_dir}/../cookbooks"]
                  cookbook_copyright "Your Company, Inc."
                    cookbook_license "apachev2"
                      cookbook_email "cookbooks@yourcompany.com"</pre>

**Yikes**, that’s no good. So instead of going line by line and re-orienting the code (which yes, I used to do), you just need to paste correctly into vi. To do this, when you're in the editing mode in vi, hit <code>:</code> and then type
<pre>set paste</pre>

Now back in the editing mode when you hit <code>i</code> for insert, you'll see the notification <code>-- INSERT (paste) --</code>. Now you can paste in and all of the formatting will look just like the original. When you're done, if you want to turn this function off, hit <code>:</code> and type
<pre>set nopaste</pre>

While this is a small thing, I __wish__ I had known about this, I don't know, like TEN YEARS AGO! :)
