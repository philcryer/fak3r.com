#!/bin/bash

#set -x

dir=$(echo $(pwd) | xargs -I% basename %)
www_dir="/var/www/html/fak3r.com/"

if [[ ! "${dir}" == "fak3r.com" ]]; then
	echo "Not in the right directory"; exit 1
fi

echo "Updating code …"
git pull

echo "Setting tor url …"
cp config.toml-tor config.toml

echo "Building …"
hugo

echo "Clearing old site …"
rm -rf ${www_dir}/*

echo "Deploying new site …"
mv public/* ${www_dir}

echo "Resetting url to www …"
cp config.toml-www config.toml

exit 0
