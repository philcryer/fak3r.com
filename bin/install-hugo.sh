#!/bin/bash
set -e

### Install Hugo or upgrade to the latest version for Linux 64bit
### 03.20.2023

# Get the URL for the latest version of Hugo from github's API
latest_hugo_url=$(curl https://api.github.com/repos/gohugoio/hugo/releases/latest) | grep "Linux-64bit.tar.gz"); echo "."

# Download the latest version of Hugo
cd /tmp; echo "."
wget --progress=dot ${latest_hugo_url}; echo "."

# Prepare users bin directory
if [ ! -d "${HOME}/bin" ]; then
    mkdir -p ${HOME}/bin
fi
echo "."

# Unpack downloaded archive
tar xvzf /tmp/hugo_*.tar.gz; echo "."

# Make hugo execuable
chmod 755 /tmp/hugo; echo "."

# Install hugo to users bin directory
mv /tmp/hugo ${HOME}/bin; echo "."

# Display hugo version
echo "Installed: \n $(${HOME}/bin/hugo version)"

# Cleanup download
rm -rf /tmp/hugo*

exit 0
