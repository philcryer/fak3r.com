---
title: "5 steps for surviving using XP in the workplace"
date: "2008-01-09T13:53:00-06:00"
categories: 
- geek
- howto
---

<strong>UPDATE</strong>: Fixed the link to [Password Safe](https://pwsafe.org/), and also learned about an updated guide covering why people shouldn’t [reuse passwords](https://pixelprivacy.com/resources/reusing-passwords/). Thanks Bill, happy to hear the project is still being actively developed, it's still needed!

Ok, I'm not typical by any means when it comes to operating systems, but desktop systems - even less so.  I've  run Linux on the desktop since ~1997, and I'm very comfortable with it...at home.  While there are a few exceptions where companies would let me run Linux on the desktop, that's not the theme of the larger companies I've frequented of late, so of course I've had  to use XP for the past 3 years, and it hasn't been all blood and roses.  First of all I can't stand the  Fisher-Price UI - it's awful, really, how dumb do I look? (that's a rhetorical question)  The first thing I do  to a new system I have to use is to revert the UI back to its 'classic' looks - at least this way I wouldn't be  reminded of how much better XP was supposed to be since they changed the way some widgets look (seriously,  right click on yr desktop, choose 'Properties' then look at that same dialog box that you saw in Windows 95!).  I usually end up  making Windows look and act as much like a Linux desktop, to make it more tolerable for me to use, but first we'll start with what really bugs us about Windows, resources that seem to be constantly straining, regardless  installed RAM!  Disclaimer: use my suggestions as just that, if things catch on fire, businesses fail, war breaks out, I can't be held responsible.  Having said that, life is short, and what's the worst that can happen?<!--more-->

<strong>I. RESOURCES</strong>

With each iteration of Windows needing more and more memory is required (which contrasts nicely with Linux),  so it is essential to make sure nothing is running on your system that you don't need.  Unfortunately Microsoft  and other 3rd party providers (RealPlayer, Apple I'm looking at you) love to have things run on your rig at  bootup to ensure they leech your system resources fulltime.  Let's rip those stole ways outta there!
<ul>
	<li>Stop things from starting - one of the most infuriating things about having to run Windows is when an  installer puts a shortcut in your startup folder. This eats up your system resources and is usually only there so  the app will 'launch faster' when you need it.  Well duh, it's already running, so sure it's going to pop up  quickly!  Go to 'Start -&gt; Programs -&gt; Startup' and delete all of those entries (anything with the words quick  or fast are likely resource hogs, kill em all)</li>
</ul>
<ul>
	<li><img src="http://www.fak3r.com/wp-content/uploads/2008/01/system-tray-thumb1.jpg" alt="Full system tray" align="right" />Kill all icons in the system tray - I love it when you get a glance at a co-worker's desktop to see that they have 20 icons in their system tray down by the clock, each one of them a placeholder for some  usually useless application that is leeching system resources while providing no benefit.  These are  usually the result of those 'startup' items we killed on the previous step, but there are plenty of other ways  for them to be started up.  First we try and reason with them, right click on each icon and try to figure out how to 'opt-out' of those lame things starting up.   If you fail to figure all of them out, don't worry some are a bit more deceptive than others, we'll fix them later.</li>
</ul>
<ul>
	<li>Kill the services - now we'll look at the big services starting up on boot as part of Windows.  Go to 'Start  -&gt; Settings -&gt; Control Panel -&gt; Administrative Tools -&gt; Services'  What needs to be running?  Turn off what you  don't need, investigate the ones you aren't sure about.  another option for this is the more consumer friendly msconfig - activate it with Start -&gt; Run 'msconfig'.  Look at the last tab, 'Startup' for a simplified version of Services.</li>
</ul>
<ul>
	<li>Lastly - HACK the registry, maliciously! - last step in reclaiming system resources is to hack the registry to stop  things from running on boot; if you haven't stopped them yet, this is the last ditch effort to shake them.  To  get into regedit you just need to go to 'Start -&gt; Run' and then type 'regedit'. Nervous types will want to make  a backup of the current registry in case of some disaster, to do this just click on 'File -&gt; Export' and save  the .reg file for backup.  Once in regedit we need to expand the tree to get to the startup section, so click  on 'My Computer -&gt; HKEY_LOCAL_MACHINE -&gt; SOFTWARE -&gt; Microsoft -&gt; Windows -&gt; CurrentVersion -&gt; Run'  Whew, ok,  take a look at the list, it should be obvious what you need to keep.  Anything that isn't anti-virus or related  to logging into the network is usually junk, but take care not to take out anything that you don't know what  it does before some Googling.  I've become quite bullish on ripping stuff out, but that comes with years of  having to deal with this junk.</li>
</ul>
<strong>II. APPLICATIONS</strong>

There are a number of applications I use and recommend others use to help make Windows work better (or just act  more like Linux), and they're all free.  Generally I'll create a 'bin' directory within My Documents to install  my new apps to, just so I can keep a tighter leash on them.
<ul>
	<li> Firefox (http://portableapps.com/apps/internet/firefox_portable) - this is a no brainier, use Firefox, it's better.  For use in an office environment I recommend using the Firefox available from Portable Apps, as I've linked to.  This way you can keep all of your  information (the app, bookmarks, cache, etc) all secure within your home directory.  Also, while we're at it, recommended settings for  Firefox include things like Privacy (don't remember history, always clear private data when you close Firefox)  and Security (use a master password) so you don't leave anything behind.</li>
</ul>
<ul>
	<li>Use IEtab for Firefox (https://addons.mozilla.org/en-US/firefox/addon/1419) - once you have Firefox installed you'll have a myriad of plugins to choose from to  further enhance your browsing experience.  In a corporate environment there's always going to be that Intranet  page or some other site that you _need_ to have IE to use.  While hacking the user agent in Firefox is fun, it  doesn't always work, so instead install IEtab.  Now a click on an icon in Firefox will switch the rendering  engine to IE, all within the confines of Firefox.</li>
</ul>
<ul>
	<li>Password Safe (https://pwsafe.org/) - originally developed by security consultant Bruce  Schneider, Password Safe is a must have to house your various passwords.  Never write down your password on a  post it note again (ever).  Easy to use .exe to run that creates the encrypted database file on the fly.  Put  those together somewhere and you'll have everything secure in one place.</li>
</ul>
<ul>
	<li>Xming (http://sourceforge.net/projects/xming) - the most up-to-date and advanced X server for Windows, simple  to setup with some nice features.</li>
</ul>
<ul>
	<li>PuTTY (http://www.chiark.greenend.org.uk/~sgtatham/putty/) - unless you're running your own X server (which  is recommended), this is the SSH client of choice for win32 users.  Also, since it's so portable, it's good to  have on hand for backup, you never know when you might need it.  Also, there is also now a newer variant called  Putty Tray (http://www.xs4all.nl/~whaa/putty/) that integrates better with Windows, and has a few additional  features.</li>
</ul>
<ul>
	<li>Cygwin (http://www.cygwin.com/) - from the site, "Cygwin is a Linux-like environment for Windows." and it's  just that, it allows you to use all the GNU tools you're used to within the Linux environment, to control  Windows.</li>
</ul>
<ul>
	<li>Launchy (http://www.launchy.net/) - much like the great Quicksilver on Macs, Launchy allows you to launch apps  with just a few key strokes.  Easy to use, a breeze to configure to point to apps in unusual (read ~/bin)  places, and always a conversation starter when I open Explorer by typing 'ex'.  It is now a 'must have' app for  me when I have to use Windows.</li>
</ul>
<ul>
	<li>XAMPP (http://www.apachefriends.org/en/index.html) - from a group calling themselves Apache Friends, XAMPP is  a single package that contains Apache, MySQL, Perl and PHP all bound together with a simple dashboard to  control it all.  If you want to quickly develop some proof of concept or just provide a Wiki to share your  documentation, this is the easiest way to do it.  It all runs out of one directory, so there's no need to  'install' anything.  It contains scripts to launch at startup as part of the Services if you need to.</li>
</ul>
<ul>
	<li>Use alt-tab to switch applications - as my use of Launchy should allude to, I use the keyboard to switch  windows constantly; trust me, it's the only way to fly.  Start getting used to that and your productivity will  increase in all desktop environments, it's a standard and it leads to (belive it or not) a better UI to work in.  How?  Read III. UI to find out.</li>
</ul>
<strong>III. UI</strong>

You know what I think of XP's default theme, but even the old one looks like a photocopy of a photocopy (insert comment about Xerox's first desktop here), and is ripe for improvement.
<ul>
	<li>Remove icons from the desktop - this relates to the alt-tab and Launchy hints; if you have to hunt and peck  for a shortcut to start an app, you're already way behind from where you should be.  Learn alt-tab, use  Launchy, keep your desk blank.</li>
</ul>
<ul>
	<li>Hide the taskbar - again, you don't need it, no more 'Start -&gt; Programs' or clicking on the  minimized  windows widget; it's already take care of by our friends alt-tab and Launchy (see a trend here?)</li>
</ul>
<ul>
	<li>Use Blackbox for Windows (http://www.bb4win.org/news.php) - the minimalist window manager from the *nix days,  Blackbox, is a great replacement to the explorer shell.  This will allow you to have decorations that differ  from the standard _ |_| X that you've had for so many years, and will take up less space.  You also gain the  menu visible by right clicking anywhere on the desktop which gives you access to the full 'Start -&gt; Programs'  dealio, plus 'Control Panel' and even those shortcuts you put on the task bar and never used.  While it doesn't  look like any of the projects (found under 'Latest Releases') are being regularly updated, don't be fooled,  people are still working on many projects for the bb4win community, and even more so are using a Blackbox  derivative for their shell.  It's completely functional and stable, even when running on top of explorer (you  can't see it, but it's still there, you're Windows taskbar will still be available, just hidden at the bottom  of your window.  I am currently running BBLean 1.16z - an oldie that much of the newer development is being  based on, and you can find tons of cool Styles that are easy to install on this site: http://www.boxshots.org/</li>
</ul>
<ul>
	<li>Use Jimmac cursors - pretty much all of the Linux distros these days use Jimmac's XFree cursors by default  and for good reason, they rock.  It's easy to install these under XP, and you'll be glad you did.  I wrote a  complete HOWTO a while back that walks you through the process here: http://fak3r.com/2007/06/21/howto-jimmac- mouse-cursors-on-xp/</li>
</ul>
<ul>
	<li>Use a NASA image for a  wallpaper (http://www.nasa.gov/multimedia/imagegallery/Previous_images_of_the_Day_Collection_archive_1.html) - take a look through the archives of great NASA images, most have a large version and make for incredible looking wallpapers.  Break out of the Digital Blashphony rut...for good.</li>
</ul>
<strong>IV. EXTRA CREDIT</strong>

For those who want to go the extra mile, here are some final ways to make your computer usage more useful at  work.  As always keep in mind your company policy on acceptable use, I can't take any responsibility for  injuries, damage, explosions, blah, blah, blah.
<ul>
	<li>Firefox 3 - while still in Beta I can attest to it being much more responsive, and far less resource hungry  (read no more memory leak), than 2 ever was.  Heck, even in it's current Beta state I'd recommend it if there  aren't any plugins you have to have from 2.</li>
</ul>
<ul>
	<li>Compose all emails in plain text - don't compose emails in HTML, or even Outlook's default RTF, use plain  text, as it's supposed to be.  Repeat after me, "Email is not an FTP protocol, Email is not an FTP protocol..."</li>
</ul>
<ul>
	<li>Disable resource stealing monitoring software - investigate suspicious entries in Task Manager, try to kill  them, if that fails dig into the registry and try to stop them from starting, then reboot.  Again, ensure that  this doesn't infringe on any 'acceptable use' guidelines at your place of employment first!</li>
</ul>
<ul>
	<li>TrueCrypt (http://www.truecrypt.org/) - encrypt a section of your harddrive if for no other reason than to  annoy your IT department.  Keep your copy of Password Safe within it, and run it out of there for extra  protection.  It even has support for a 'hidden volume' so if you're forced to give up your passkey they can  look and not see any of the 'real' secret data you have.  Ooo, how l33t you are!  So don't try to be smug about  it, but you know you're a badass, so you might as well flaunt it a little bit.</li>
</ul>
<ul>
	<li>BSOD wallpaper - find a nice Blue Screen Of Death image that will fit as a wallpaper from somewhere like  Google Images: http://images.google.com/images?q=bsod&amp;svnum=10&amp;um=1&amp;hl=en&amp;safe=active&amp;sa=N&amp;imgsz=xxlarge -  again, a nice conversation starter.</li>
</ul>
<ul>
	<li>Evangelize Linux - I highly recommend asking about running Linux on your workstation, at least as a pilot.   While the outcome is usually not a positive one, it can demonstrate to others that you want to improve the  environment, save some bucks for the company or more.  In the least it can give them an idea to keep you in mind  if anything Linux-y comes up in the future.  But I really recommend asking about running Linux on during the  interview process.  Remember, when you're in an interview you should be looking at them as hard as they're  looking at you; is this where you want to work, with the constraints laid out ahead of time?  Again, as above,  it shows that you're "thinking outside of the box" (I hate that one) with new ideas.  At the very worst they'll  despise you for asking and not hire you, which is perfect since why you wouldn't be happy working for people  that close minded.</li>
</ul>
<strong>V. CONCLUSION</strong>

I hope this guide has prepared you to better deal with Windows XP in the workplace, or at least made you realize that your hatered is shared among many.  Let's face it, it's not the best tool for  the job, but as long as you have to use it you might as well take control of it to make it run better, and be less annoying to deal with.  Maybe you'll find a place to work that allows, or even encourages, the use of Linux or Apple, and there are  places are out there, and that will certainly help you survive your time with XP.

Do you have a suggestion that I haven't covered?  Some nugget of advice you've cultivated over the years of  indentured servitude to the MS behemoth?  Sound off in the comments.
