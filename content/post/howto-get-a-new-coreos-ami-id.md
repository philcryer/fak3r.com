+++
title = "HOWTO Get a New CoreOS AMI ID"
Description = "Determine the AMI ID for a CoreOS image from the commandline"
date = "2015-07-09T21:12:06-06:00"
Categories = ["howto", "geek"]
Tags = ["howto", "docker", "coreos"]

+++
I've used [CoreOS](https://coreos.com/) a good deal for the last few months, automating it on [Amazon Web Services](http://aws.amazon.com/) to run [Docker](https://www.docker.com/) instances like a boss, but when a new version comes out, figuring out the new AMI ID to target is cumbersome. What happens is that a new CoreOS version will be built with AWS, resulting in a new AMI ID, but going to the CoreOS [cloud provider's page](https://coreos.com/docs/running-coreos/cloud-providers/ec2/) to manually grok the ID is no fun. I knew there had to be a automated way to do this, but earlier attempts failed. That changed today as I got a clue from the <i>#coreos</i> channel on [irc.freenode.net] (h/t guys!). Here's the [gist](https://gist.github.com/philcryer/4a4ed1d0142af00a442c):
 
<!--more-->
<script src="https://gist.github.com/philcryer/4a4ed1d0142af00a442c.js"></script>

<div align="center"><img src="/2015/coreos-logo.png" border="0" alt="CoreOS"></div>

If you pull that, and run it, calling out the region you want the AMI in, you'll get the details. For example:

```
# coreid.sh us-east-1
us-east-1,alpha,hvm,ami-ff35fc94,https://console.aws.amazon.com/ec2/home?region=us-east-1#launchAmi=ami-ff35fc94
```

Now we have all the details, and for me, most importanly the AMI ID and the URL to launch the AMI. Cool, so that's it, I'm calling this day a success. Is there an easier way of doing this? Am I missing something obvious? Let me know, always happy to learn more.
