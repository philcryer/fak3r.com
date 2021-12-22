git add .
git commit -m 'updating $(date)'
git push
./bin/deploy-onion-ssh.sh
