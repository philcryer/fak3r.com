---
title: "Sync or backup files to Wasabi Cloud Storage"
date: "2024-08-03T07:42:19-05:00"
Tags: ["backup", "rclone", "wasabi", "cloud-backup"]
Categories: ["howto"] 
draft: false
---
<figure>
<div align="center" />
    <img src="/2024/sync-or-backup-files-to-wasabi.png" alt="Sync or backup files to Wasabi" /><br />
    <figcaption>Sync or backup files to Wasabi</figcaption>
</div>
</figure>
 
## Summary
 
I use [Wasabi](https://wasabi.com/) for cloud storage, and this script I've developed makes it simple to keep my files synced with Wasabi's Cloud Object Storage. I choose Wasabi because it is:
* [100% bit-compatible with Amazon S3](https://docs.wasabi.com/docs/since-wasabi-is-100-bit-compatible-with-amazon-s3-can-i-use-my-existing-s3-compatible-application-without-making-any-changes-to-my-application-with-wasabi) this means that it follows an accepted standard for cloud storage and interoperability, while allowing me to use existing S3 tools if I wanted to
* Since it's S3 compatible I don't have to install some GUI tool or have some sort of extention to my file manager to be able to use the service. This is a big deal because I want to setup a sync process to run backups, schedule it and just let it run, all doable via code
* It's more affordable, partially because they don't charge for egress (download tranfers) whereas most other cloud storage does
  - Granted this is clearly [their own marketing](https://knowledgebase.wasabi.com/hc/en-us/articles/360002435072-How-does-the-cost-of-Wasabi-compare-to-the-true-cost-of-AWS-S3), in this example, "_$599/mo with Wasabi and over $3,500/mo with AWS S3* (AWS is 7x more expensive!)_"
  - Personally I'm paying less than $15.00 USD per month to store almost 1.5 Terabyte (TB)
* It supports [Aegis Authenticator](https://getaegis.app/) my preferred 2FA application
 
So basically it uses a well known standard instead of trying to reinvent the wheel, it doesn't require any proprietary tools to function, it's less expensive than other competing options, while being something I can easily script and throw in cron, actually systemd but we'll cover that, to just set and forget.
 
## Requirements
 
The scripts use rsync to organize local files to be synced and rclone to sync files to Wasabi Cloud so first we need to install those
 
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
 
* edit the timer file to assign when it should run, just uncomment one of the `onCalendar` lines for Daily or Custom, or do nothing and leave Weekly (the default I've set it to) uncommented for it to kick off every Monday at midnight (00:00:00)
 
* by default, systemd only runs timers if the user is logged in so to be able to run timer jobs without being logged in enable lingering session
 
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
> This will walk you through configuring rclone to have access to your Wasabi account and buckets to sync with
 
* configure the `wasabi-sync` script and update the variables near the top of the script to match your configuration
  
  - `local_path` this is the base path to the directory you want to sync, my example:
 
```shell
local_path="/media/${USER}/external-drive"    
```
 
  - `directory_names` the local directories within the `local_path` that you want synced to Wasabi. I have Backups, Music, and Pictures directories I populate on my backup drive
 
```shell
directory_names=("Backups Music Pictures")      
```
 
  - `wasabi_pref` this is the naming prefix you've defined during the Wasabi config steps above. This prevents naming collitions, for example, if you tried to name a bucket `Documents` it's likely already taken, but something like `fak-Documents` probably wouldn't be. Wasabi will guide you on this when you create your buckets on their site 
 
```shell
wasabi_pref="fak"
```
 
  - `wasabi_envi` this is your Wasabi region where your buckets reside
 
```shell
wasabi_envi="wasabi-us-east-1"
```
 
  - wasabi-sync-local (optional) I wrote a simple script that uses `rysnc` to gather files/directories I want to backup ahead of main script and compile them to a Backup directory, so I don't backup extraneous files/directories I don't need to backup. Check the script for examples, uncomment any you want, or add your own
 
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
 
It should show the service and timer being enabled, that's what we want to start on boot and keep running, and you should see green dots showing it's ready and running
 
### Test
 
* to test that the script works as intended, start the service manually
 
```shell
systemctl --user start wasabi-sync.service
```
 
> [!NOTE]
> This oneshot approach works because in the timer feil we have this set: RefuseManualStart=no 
 
You can view what the script is doing overall by tailing the wasabi-sync.log file in real-time
 
```shell
tail -f ~/.wasabi-sync.log
```
 
To see the full detail of what's happening, you can follow the systemd logfile
 
```shell
tail -r ~/.wasabi-sync-systemd.log
```
 
> [!NOTE]
> I use this when I'm debugging, after everything is working, I comment out `StandardOutput=file:%h/.wasabi-sync-systemd.log` in wasabi-sync.service, followed by another daemon-reload command to reload the service with the new values
 
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
