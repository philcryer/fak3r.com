#!/bin/bash
set -e
git pull
hexo clean
hexo generate
rm -rf /var/www/fak3r.com/*
mv public/* /var/www/fak3r.com/
chown -R www-data:www-data /var/www/fak3r.com/
exit 0
