#!/bin/bash
#
# script to build website and push it to github

# create a tmp dir into which jekyll will build the html source
if [ -d "/tmp/jekyll_build" ]; then
    rm -rf /tmp/jekyll_build
fi
mkdir /tmp/jekyll_build

# build website
jekyll build -d /tmp/jekyll_build/

# publish on github only if jekyll build was successful
if [ $? -eq 0 ]; then
    cd /tmp/jekyll_build
    git init
    git add .
    publish_date=`date`
    git commit -m "updated site ${publish_date}"
    git remote add origin git@github.com:varunbpatil/varunbpatil.github.com.git
    git push origin master --force

    echo "Successfully built and published to github..."
else
    echo "Jekyll build failed... not publishing to github"
fi

# cleanup
rm -rf /tmp/jekyll_build
