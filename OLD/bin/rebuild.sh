#!/bin/bash
export COMMIT_DATE=$(date)
git add .
git commit -m "updating on $COMMIT_DATE"
git push
#./bin/deploy-onion-ssh.sh
#./bin/deploy-onion.sh
