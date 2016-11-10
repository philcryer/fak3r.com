#!/bin/bash

# Install Hugo or upgrade a newer version

HUGO_VERSION=0.17

LATEST=$(curl https://github.com/spf13/hugo/releases/latest | tail -n1 | cut -d"/" -f8 | cut -d"\"" -f1)

set -e

wget https://github.com/spf13/hugo/releases/download/v${HUGO_VERSION}/hugo_${HUGO_VERSION}_linux_amd64.tar.gz

tar xvzf hugo_${HUGO_VERSION}_linux_amd64.tar.gz

if [ ! -d '$HOME/bin' ]; then
    mkdir $HOME/bin
fi

cp hugo_${HUGO_VERSION}_linux_amd64/hugo_${HUGO_VERSION}_linux_amd64 $HOME/bin/hugo

rm -rf hugo_${HUGO_VERSION}_linux_amd64*

exit 0
