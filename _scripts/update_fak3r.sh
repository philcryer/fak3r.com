#!/bin/bash

web_prd="/var/www/fak3r.com"
web_dev="/var/www/beta.fak3r.com/fak3r.com/_beta"
web_usr="www-data"
web_grp="www-data"

rm -rf $web_prd/*
cp -R $web_dev/* $web_prd
ln -s $web_prd/atom.xml $web_prd/rss.xml
chown -R $web_usr:$web_grp $web_prd

exit 0
