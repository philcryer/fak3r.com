#!/bin/bash

#set -x

echo "Setting tor url …"
cp config.toml-tor config.toml

echo "Building …"
hugo

echo "Clearing old site …"
rm -rf /var/www/html/fak3r.com/*

echo "Deploying new site …"
mv public/* /var/www/html/fak3r.com/

echo "Resetting url to www …"
cp config.toml-www config.toml

exit 0
