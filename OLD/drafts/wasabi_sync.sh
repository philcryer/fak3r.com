#!/bin/bash

#### wasabi_sync.sh
#
# - a bash script that uses rclone to sync with Wasabi https://wasabi.com/
# - runs with safe nice, ionice, and cfq (completely fair queuing) settings
# - uses a lockfile to make sure only one copy of the script runs at once
# - copyright is under a very permissive open source MIT License
# - example command (change path to match yours):
# 	nice -n 19 ionice -c2 -n7 /bin/wasabi_sync.sh
# - example crontab line (change path to match yours):
# 	0 3 * * 1 /usr/bin/nice -n 19 ionice -c2 -n7 /bin/wasabi_sync.sh
#
####

#### Copyright 
#
# MIT LICENSE
#
# Copyright 2023 Phil Cryer<phil@philcryer.com>
#
# Permission is hereby granted, free of charge, to any person obtaining a copy
# of this software and associated documentation files (the “Software”), to
# deal in the Software without restriction, including without limitation the
# rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
# sell copies of the Software, and to permit persons to whom the Software is
# furnished to do so, subject to the following conditions:
#
# The above copyright notice and this permission notice shall be included in
# all copies or substantial portions of the Software.
#
# THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
# IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
# FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
# AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
# LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
# FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
# IN THE SOFTWARE.
#
####

#### Lockfile
#
# - set a lockfile so that only one version of this script runs at once
# - install procmail to get the lockfile binary (apt install procmail)
# - source: https://www.baeldung.com/linux/bash-ensure-instance-running
#
LOCK=/var/lock/wasabi-sync.lock
remove_lock()
{
    rm -f "$LOCK"
}
another_instance()
{
    echo "There is another instance running, exiting"
    exit 1
}
lockfile -r 0 -l 3600 "$LOCK" || another_instance
trap remove_lock EXIT
#
####

#### Sync local files to wasabi
#
# - adjust to fit your file paths, wasabi regions, and wasabi bucket namest
#
echo "---- Run started on $(date)" >> ${HOME}/.wasabi_sync.log
#
echo " * DOCUMENTS ************************************************************"
rclone -v sync /mnt/phil/Elements-5T/Documents/ wasabi-us-east-1:pc-documents/
echo " **** DOCUMENTS ran on $(date)" >> ${HOME}/.wasabi_sync.log

echo " * GAMES ****************************************************************"
rclone -v sync /mnt/phil/Elements-5T/Games/ wasabi-us-east-1:pc-games/
echo " **** GAMES ran on $(date)" >> ${HOME}/.wasabi_sync.log

echo " * PICTURES *************************************************************"
rclone -v sync /mnt/phil/Elements-5T/Pictures/ wasabi-us-east-1:pc-pictures/
echo " **** PICTURES ran on $(date)" >> ${HOME}/.wasabi_sync.log

echo " * MUSIC ****************************************************************"
rclone -v sync /mnt/phil/Elements-5T-2TB/Music/ wasabi-us-east-1:pc-music/
echo " **** MUSIC ran on $(date)" >> ${HOME}/.wasabi_sync.log

echo " * MOVIES ***************************************************************"
rclone -v sync /mnt/phil/Elements-5T/Movies/ wasabi-us-east-1:pc-movies/
echo " **** MOVIES ran on $(date)" >> ${HOME}/.wasabi_sync.log

echo " * MUSIC-VIDEOS**********************************************************"
rclone -v sync /mnt/phil/Elements-5T/Music-Videos/ wasabi-us-east-1:pc-music-videos/
echo " **** MUSIC-VIDEOS ran on $(date)" >> ${HOME}/.wasabi_sync.log

echo " * TV-SHOWS *************************************************************"
rclone -v sync /mnt/phil/Elements-5T/TV-Shows/ wasabi-us-east-1:pc-tv-shows/
echo " **** TV-SHOWS ran on $(date)" >> ${HOME}/.wasabi_sync.log
#
####

#### Timestamp
#
echo "---- Last run completed on $(date)" >> ${HOME}/.wasabi_sync.log
#
####

exit 0
