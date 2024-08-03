---
title: "Fixing SSH issues in Windows"
date: "2024-08-03T07:42:19-05:00"
Tags: ["backup", "rclone", "wasabi", "cloud-backup"]
Categories: ["howto"] 
draft: false
---
<figure>
<div align="center" />
    <img src="img/sync-or-backup-files-to-wasabi.png" alt="Sync or backup files to Wasabi" /><br />
    <figcaption>Sync or backup files to Wasabi</figcaption>
</div>
</figure>

## Summary

I use [Wasabi](https://wasabi.com/) for cloud storage, and this script I've developed makes it simple to keep my files backed up to Wasabi's Cloud Object Storage. I choose Wasabi because it is:
* [100% bit-compatible with Amazon S3](https://docs.wasabi.com/docs/since-wasabi-is-100-bit-compatible-with-amazon-s3-can-i-use-my-existing-s3-compatible-application-without-making-any-changes-to-my-application-with-wasabi) this means that it follows an accepted standard for cloud storage and interoperability, while allowing me to use existing S3 tools if I wanted to
* Since it's S3 compatible I don't have to install some GUI tool or have some sort of extention to my file manager to be able to use the service. This is a big deal because I want to setup a sync process to run backups, schedule it and just let it run, all doable via code
* It's more affordable, partially because they don't charge for egress (download tranfers) whereas most other cloud storage does
  - Granted this is clearly [their own marketing](https://knowledgebase.wasabi.com/hc/en-us/articles/360002435072-How-does-the-cost-of-Wasabi-compare-to-the-true-cost-of-AWS-S3), in this example, "_$599/mo with Wasabi and over $3,500/mo with AWS S3* (AWS is 7x more expensive!)_"
* It supports [Aegis Authenticator](https://getaegis.app/) my preferred 2FA application

so it uses a standard instead of trying to reinvent the wheel, it doesn't require any tools that you have to install, so it's something I can script and throw in cron (but actually systemd, but that's cover later).
it's a 

## Requirements

The scripts use rsync to organize local files to be synced and rclone to sync files to Wasabi Cloud

* Debian/Ubuntu or other Debian variants 

```shell
apt install rsync rclone
```

* Fedora or other Red Hat variants

```shell
dnf install rsync rclone
```

* Homebrew (MacOS / Linux)

```shell
brew install rsync rclone
```

## Steps

### Install

* wasabi-sync and wasabi-sync-local

```shell
git clone git@github.com:philcryer/wasabi-sync.git
cd wasabi-sync
mkdir -p ~/bin
cp wasabi-sync wasabi-sync-local ~/bin
chmod 0755 ~/bin/wasabi-sync*
```

* setup systemd automation task

```shell
mkdir -p ~/.config/systemd/user/
cp systemd/wasabi-sync.service systemd/wasabi-sync.timer ~/.config/systemd/user/
```

* edit the timer file to assign when it should run, just uncomment one of the `onCalendar` lines for Daily or Custom, or do nothing and leave Weekly (the default here) uncommented for it to kick off every Monday at midnight (00:00:00)

* by default, systemd only runs timers if the user is logged in so to be able to run timer jobs without logged in enable lingering session with

```shell
loginctl enable-linger ${USER}
```

* reload systemd to allow it to load the new timer and service files

```shell
systemctl --user daemon-reload 
```

### Configure

Now we need to setup `wasabi-sync` to work in your environment

### Rclone

* setup rclone to access Wasabi cloud by following their [rclone documentation](https://rclone.org/s3/#wasabi). See that page for all of the current steps required. 

```shell
rclone config

n/s> n
name> wasabi

...
```

> [!NOTE]
> This will walk you through configuring the buckets on Wasabi that you want to sync to

* configure the `wasabi-sync` script and update the variables to match your configuration
  
  - `local_path` this is the base path to the directory you want to sync, my example:

```shell
local_path="/media/${USER}/external-drive"    
```

  - `directory_names` the local directories within the `local_path` that you want synced to Wasabi

```shell
directory_names=("Backups Music Pictures")      
```

  - `wasabi_pref` this is your prefix you've defined for your buckets in Wasabi

```shell
wasabi_pref="fak"
```

  - `wasabi_envi` this is your Wasabi region where your buckets reside

```shell
wasabi_envi="wasabi-us-east-1"
```

  - wasabi-sync-local (optional) I wrote a simple script that uses `rysnc` to gather files/directories I want to backup ahead of main script and complie them to a Backup directory so I don't backup extraneous files/directories I don't need to backup. Check the script for examples, uncomment any you want, or add your own

### Enable

* tell systemd to enable the service and timer

```shell
systemctl --user enable wasabi-sync.service --now
systemctl --user enable wasabi-sync.timer --now
```

* check the status of the service and timer so you're sure there are no configuration errors

```shell
systemctl --user status wasabi-sync.service wasabi-sync.timer
```

It should show the seriver and timer being enabled, that's what we want to start on boot and keep running, and you should see green dots showing it's ready and running

### Test

* to test that the script works as intended, start the service manually

```shell
systemctl --user enable wasabi-sync.service
```

> [!NOTE]
> This oneshot approach works because in the timer feil we have this set: RefuseManualStart=no 

You can view what the script is doing overall by tailing the wasabi-sync.log file in real time

```shell
tail -f ~/.wasabi-sync.log
```

To see the full detail of what's happening, you can follow the systemd logfile

```shell
tail -r ~/.wasabi-sync-systemd.log
```

> [!NOTE]
> I use this when I'm debugging, after everything is working I comment out `StandardOutput=file:%h/.wasabi-sync-systemd.log` in wasabi-sync.service

## Example

As I said above, I have 3 directories, Backups, Music, and Pictures that I'm syncing to buckets in Wasabi. A current log file from a run shows details including runtime and size of the remote buckets after the run in Wasabi

```shell
[*] Run started at Fri Aug  2 10:37:13 AM CDT 2024
    Local-sync:
      + started: 2024-08-02T10:37:13-05:00
      + completed: 2024-08-02T10:37:17-05:00
    Backups:
      + started: 2024-08-02T10:37:17-05:00
      + completed: 2024-08-02T11:06:35-05:00
      + size: 189.448 GiB
    Music:
      + started: 2024-08-02T11:07:04-05:00
      + completed: 2024-08-02T12:09:26-05:00
      + size: 1.362 TiB
    Pictures:
      + started: 2024-08-02T12:09:41-05:00
      + completed: 2024-08-02T12:26:17-05:00
      + size: 79.937 GiB
[*] Run completed at Fri Aug  2 12:26:22 PM CDT 2024
```

## License

[MIT License](LICENSE)

### Thanks
