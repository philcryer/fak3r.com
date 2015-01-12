#!/bin/bash
set -e
# souper simple hexo deploy script
# * check that pwd == dir in docroot
# * check user/group of the webserver
dir_name=`printf '%s\n' "${PWD##*/}"`
web_user="www-data"
web_grp="www-data"
doc_root="/var/www/"$dir_name
git pull
hexo clean
hexo generate
rm -rf $doc_root/*
mv public/* $doc_root/
chown -R $web_user:$web_grp $doc_root
exit 0
