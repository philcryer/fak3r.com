#!/bin/bash

#set -x

dir=$(echo $(pwd) | xargs -I% basename %)
base_dir="devel/fak3r.com/"
www_dir="/var/www/html/fak3r.com/"

if [[ ! "${dir}" == "fak3r.com" ]]; then
	echo "Not in the right directory"; exit 1
fi

ssh stand "cd ${base_dir}; git pull; cp config.toml-tor config.toml; hugo; rm -rf ${www_dir}/*; mv public/* ${www_dir}; cp config.toml-www config.toml; exit 0"

exit 0
