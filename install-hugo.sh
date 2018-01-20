#!/bin/bash

set -e

# Install Hugo or upgrade a newer version

# Figure out the lastest version of Hugo
LATEST=$(curl https://github.com/gohugoio/hugo/releases/latest | cut -d"/" -f8 | cut -d"v" -f2 | cut -d"\"" -f1)
HUGO_VERSION="${LATEST}"

# Or choose a specific version
#HUGO_VERSION=0.17

if [ `uname` == "Darwin" ]; then
  HUGO_EXEC="hugo_${HUGO_VERSION}_macOS-`uname -m|cut -d"_" -f2`bit"
else
  HUGO_EXEC="hugo_${HUGO_VERSION}_`uname`-`uname -m|cut -d"_" -f2`bit"
fi

cd /tmp; wget -O ${HUGO_EXEC}.tar.gz "https://github.com/spf13/hugo/releases/download/v${HUGO_VERSION}/${HUGO_EXEC}.tar.gz"

tar xvzf ${HUGO_EXEC}.tar.gz

if [ ! -d '$HOME/bin' ]; then
    mkdir $HOME/bin
fi

cp hugo $HOME/bin/hugo

chmod 755 $HOME/bin/hugo

rm -rf ${HUGO_EXEC}*

exit 0
