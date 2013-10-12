#!/bin/bash

jekyll build -d /tmp/jekyll_build/

if [ $? -eq 0 ]; then
    cd /tmp/jekyll_build
    git init
    git add .
    publish_date=`date`
    git commit -m "updated site ${publish_date}"
    git remote add origin git@github.com:philcryer/fak3r.com.git
    git push origin master --force

    echo "Successfully built and published to github..."
else
    echo "Jekyll build failed... not publishing to github"
fi

exit 0
