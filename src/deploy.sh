#!/bin/bash
set -e

# souper simple hexo deploy script
# * check that pwd == dir in docroot
# * check user/group of the webserver

# variables
sitedir=`printf '%s\n' "${PWD##*/}"`
# uncomment next line to test sitedir varible
#echo $sitedir; exit 1
sitepath='/var/www/'${sitedir}
htuser='www-data'
htgroup='www-data'

# code
git pull
hexo clean
hexo generate
rm -rf ${sitepath}/*
mv public/* ${sitepath}/
hexo clean

# permissions
find ${sitepath}/ -type f -print0 | xargs -0 chmod 0640
find ${sitepath}/ -type d -print0 | xargs -0 chmod 0750
chown -R root:${htgroup} ${sitepath}/

exit 0
