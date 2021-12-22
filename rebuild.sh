#!/bin/bash

git add .
git commit -m 'updating on $(date)'
git push
./bin/deploy-onion-ssh.sh
