---
title: "Firefox 2.0 tweaks"
slug: "firefox-20-tweaks"
date: "2006-10-26T11:29:18-06:00"
author: "fak3r"
categories:
- geek
tags:
- code
---

<!-- more -->


* * *


First, the only add-on or extension that I install is called [Fission](https://addons.mozilla.org/firefox/1951/). Fission allows you to combine the address bar and the progress bar, allowing for the Safari feel of page loading, which is something I think Apple did very well.









Another of its options (and one that I now use) is to also allow the 'Status bar' messages to appear in the Address bar, this way when a page is loading, or when your cursor hovers over a link, the URL appears in the Address bar.  Now you can click 'View -> Status bar' and turn it off, giving you a tad more real estate, but also making the browser that much cleaner.  Hard to explain, but really nice in my mind.  Check out Fission's preferences for a guide.


> Side note: if you're running Firefox on Apple and you don't like the fact that Firefox may not blend in with the OS X look as much as you'd like, checkout the themes over at [GrApple](http://takebacktheweb.org/).  Their themes are *very* nice, and you can see a bit of one of them in action in the above screen shot.


Now come a few configuration tweaks to solve a few annoyances.  First comes turning off the amazingly annoying tool tips.  This is especially an issue when you have a URL you want to add to the toolbar, you go to drag it, but the cursor sees another link below it, and you get the big yellow box with the link title and description.  Plus, I already know how to use Firefox, and I can read my links, so I don't need boxes telling me what I already know.  Unfortunately it's not an option you can turn off in the Preferences, you have to modify the configuration by hand.  Luckily Mozilla developers have made this easy, with almost a GUI front end to ensure you don't screw things up too badly.  So, to make this change, in the address bar enter:


> `about:config`


...and press Enter.  Now you'll see a long list of options, and also a search field just below the address bar.  In this search field, cut/paste in:


> `browser.chrome.toolbar_tips`


Once it comes up, right click on it, and choose 'Toggle', which will change its value from True to False, thus turning it off.

The next option, more of a performance/privacy option.  In order to speed up page loading Firefox will try and load links on you current page in the background based (somehow) on what it thinks are the most popular links on the page.  Dunno how it knows this, if it 'phones home' or what, but it's not needed in these days of 1 Meg broadband everywhere.  Turn it off by searching for:


> `network.prefetch-next`


Again, right click on it, and choose 'Toggle', which will change its value from True to False, and turn it off.

Once you start using tabs you can't stop, and I find myself always having a ton of them open.  In 2.0 it nicely adds more by scrolling them off to the side and providing a new arrow button to show you that you have ones offscreen to view, and provide you a means of scrolling to them.  Very nice, but for my tastes it tends to start scolling things earlier than I'd like, and I end up having to really scroll around to do my work.  This tweak simply makes it so you'll have more visable before the scrolling kicks in.  Search for:


> `browser.tabs.tabMinWidth`


Right click and choose 'Modify'  Set it to 75 and see if that suits your taste, or 0 to disable scroll completely (the default is 100)

Lastly everyone always complains about how much memory Firefox eats up, but I suspect this is just a matter of people like me opening 100 tabs at once, but whatever.  This tweak cuts down on the browser cache, effectively making Firefox a better neighbor to your other RAM hungry apps.  To change it, search for:


> `browser.cache.disk.capacity`


Then right click and choose 'Modify".   The new value you use depends on your system's total memory. According to Computerworld: For RAM sizes between 512BM and 1GB, start with 15000. For RAM sizes between 128MB and 512M, try 5000.

Lastly, to take away some of the extra clutter for more real estate, update some chrome.  Firefox is very configurable, but there are things that aren't handled via the 'customized' menu.  Cut/paste this text into userChrome.css (located in your Mozilla Application Data folder, or ~/.mozilla/profiles/[randomchars].default/chrome) to make the changes listed (ie- removing the 'Go' button, removing the 'throbber', etc)


> `/*
* Edit this file and copy it as userChrome.css into your
* profile-directory/chrome/
*/`/*
* This file can be used to customize the look of Mozilla's user interface
* You should consider using !important on rules which you want to
* override default settings.
*/

/*
* Do not remove the @namespace line -- it's required for correct functioning
*/
@namespace url("http://www.mozilla.org/keymaster/gatekeeper/there.is.only.xul"); /* set default namespace to XUL */

/*
* Some possible accessibility enhancements:
*/
/*
* Make all the default font sizes 20 pt:
*
* * {
*   font-size: 20pt !important
* }
*/
/*
* Make menu items in particular 15 pt instead of the default size:
*
* menupopup > * {
*   font-size: 15pt !important
* }
*/
/*
* Give the Location (URL) Bar a fixed-width font
*
* #urlbar {
*    font-family: monospace !important;
* }
*/

/*
* Eliminate the throbber and its annoying movement:
*
* #throbber-box {
*   display: none !important;
* }
*/

/*
* For more examples see http://www.mozilla.org/unix/customizing.html
*/
/* Remove the Edit and Help menus
Id's for all toplevel menus:
file-menu, edit-menu, view-menu, go-menu, bookmarks-menu, tools-menu, helpMenu */
#helpMenu, #edit-menu {    display: none !important; }

/* Remove Home button */
#home-button { display: none; }

#go-button-stack, .search-go-button-stack {
display: none !important;
}

/* Eliminate the throbber and its annoying movement: */
#throbber-box { display: ; }
