---
author: phil
comments: true
date: 2006-06-05 18:33:00
layout: post
slug: howto-get-typo-themes-to-work-with-trunk
title: HOWTO get Typo themes to work with Trunk
wordpress_id: 4
categories:
- General
tags:
- hacker
- howto
---

I wrote to the mailing list last week to inquire about what themes work with Typo trunk.  After a response I found that a fix had already been posted out there in the Internetland.  So, mad props and shout outs go to [Piers Cawley](http://www.bofh.org.uk/) for posting the solution to getting all the great themes from the [Typo themes contest](http://typogarden.org) to work with Typo Trunk (most themes are br0k3d due to some basic changes in Typo’s API).  Piers writes, ”_If you’ve still got problems porting an old theme, it’s probably because your theme renders sidebars ‘wrong’. Check in your themes//layouts/default.rhtml and look for the line that looks like:_”


> <%= render_component(:controller => 'sidebars/sidebar', :action`=>'display_plugins') %>`







> 
and replace it with:


> 

>     
>     <code><%= render_sidebars %></code>
> 
> 



If that doesn’t fix things, his next solution is, ”_The other big change in the way themes are rendered (and again, its’ sidebar related) is in the  section of the layout. Here’s what the default theme header looks like nowadays:_”


> 

> 
> <head>
<title><%=h page_title %></title>
<%= page_header %>
<%= stylesheet_link_tag "/stylesheets/theme/azure", :media => 'all' %>
<%= stylesheet_link_tag "/stylesheets/user-styles", :media => 'all' %>
<%= stylesheet_link_tag "/stylesheets/theme/print", :media => 'print' %>
</head>“_You should change yours to look similar (you pretty much must include that <%= page_header %> part_”
> 
> 





> 
