#!/bin/bash

#set -x

echo "setting url.."
cp config.toml-tor config.toml

echo "building.."
hugo

echo "clearing old site.."
rm -rf /var/www/html/fak3r.com/*

echo "deploying new site.."
mv public/* /var/www/html/fak3r.com/

echo "resetting url.."
cp config.toml-www config.toml

exit 0
