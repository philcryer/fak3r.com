---
title: "Upgrading GitLab from 6x to 10x"
description: "Adventures in updating, and migrating, an old GitLab server to the latest version"
draft: false
tags: [ "howto", "gitlab" ]
topics: [ "sysadmin" ]
date: "2017-12-11T19:14:56-06:00"
---

<p><img src="/2017/gitlab.png" width="330" height="150" align="right">Recently at my day job, the client tasked me with bringing their development stack up to date. The (fun) difficultly here was that these servers were about 4 years out of date and included Jenkins, Sonar, <a href="https://gitlab.com">GitLab</a>), and Nexus. Jenkins was the jumbled mess you'd expect with unsupported plugins, new configurations breaking builds, and more fun, Sonar was so out of date I started from scratch after dropping the old databse, but GitLab was the one I was looking forward to, and while it was enlightening, it wasn't much fun! First I'll say that I'm a big fan of GitLab, it's the only thing I'd want to use internally at a company, and the community edition has a ton of great features. The main issue with my setup was that it was so out of date and required us to step up one major verision at a time; so 6.x up to 7.x, 7.x up to 8.x, 8.x up to 9.x, and finally, 9.x up to 10.2.x.</p>

<!-- more -->

## The Plan

After trying, failing, trying again, and failing again to upgrade GitLab, I came up with some tricks that helped me (eventually) succeed.

* Upgrade to a new server. The upgrade, migrate, backup, restore proess takes time, and if you have a lot of repos it takes a lot more time, so plan on having your PROD server that you'll only backup from, and sync repositories from, and a DEV server that you can restore your GitLab install to, and then migrate to the current GitLab version.
* Backup/Restore to the same version and type of GitLab. Once you backup PROD, you have to have DEV running the same version and type of GitLab. So for me, I had GitLab 6.9.2-ee (Enterprise Edition), so I had to restore to the same thing. Later I was able to use the GitLab repos to 'downgrade' to later GitLab-ce (community edition) version, which is all we needed. We had GitLab-ee because our IT department thought we needed it to use Active Directory, but that is in the CE version.
* Seperate the DB from the repos. The backup process does a DB dump, then tars that `.sql` file with **all** of the repositories. This gets unwieldy quickly, and takes far longer to backup and restore. A better move is just to use the `gitlab-rake` tool to backup the DB, and then just use `rsync` to transfer the repositories over, makes this far easier, and less of a hassle if you have to do the backup/restore dance multiple times (like me).

## Getting started 

### Install GitLab on the DEV server

First get GitLab installed on DEV, as mentioned make sure it is the same version/type as you have installed in PROD. Get it up and running, don't worry about configuring it too much because that's all going to get overwritten, just make sure it's up and working.

### Backup the PROD database

* on the PROD server, use the built in rake command to backup the DB on the old server, skipping the repos and uploads (if you can).

```
gitlab-rake gitlab:backup:create SKIP=repositories,uploads
```

**NOTE** This didn't work for me, I don't know if it's because we were using such an outdated version or what, but while the above command worked (it didn't give an error, but also didn't SKIP the things I asked it to), the real command for this step was:

```
gitlab-rake gitlab:backup:create
```

* from the PROD server, copy the newly created backup tarfile to the DEV host

```
scp /var/opt/gitlab/backups/1511809717_gitlab_backup.tar svcansible@DEV:/tmp
```

* from the PROD server, copy existing `/etc/ssh` and `/etc/gitlab` configuration

```
scp -r /etc/ssh /etc/gitlab svcansible@DEV:/tmp
```

* if you were able to seperate the DB and repositories, use `rsync` to copy the repos to the DEV server

```
rsync -avz /var/git-data/repositories/ git@domain-dev.com:/var/git-data/repositories
```

* on the DEV host import the directories `/etc/ssh` and `/etc/gitlab`, this should allow users to seemlessly transition to the new server

```
mv /etc/ssh /etc/ssh-dist
cp -R ~/new/ssh ~/new/gitlab /etc
chgrp ssh_keys /etc/ssh/*_key
```

* edit gitlab url hostname in `/etc/gitlab/gitlab.rb` to reflect the current host

```
external_url "https://domain-dev.com/"
```

* and correct the path and names of the SSL key and certs files to use

```
nginx['redirect_http_to_https'] = true
nginx['ssl_certificate'] = "/etc/gitlab/ssl/gitlab.crt"
nginx['ssl_certificate_key'] = "/etc/gitlab/ssl/gitlab.key"
```

* import the PROD backup tarfile to DEV

```
cp /tmp/1511982248_gitlab_backup.tar /var/opt/gitlab/backups/
chown git:git /var/opt/gitlab/backups/1511982248_gitlab_backup.tar
```

* stop the processes that are connected to the database. Leave the rest of GitLab running:

```
gitlab-ctl stop unicorn
gitlab-ctl stop sidekiq
# Verify those two processes are Down
gitlab-ctl status
```

* import the DB backup (and optionally the repos)

```
gitlab-rake gitlab:backup:restore BACKUP=1511809717
```

at the end you're asked:

```
This will rebuild an authorized_keys file.
You will lose any data stored in authorized_keys file.
Do you want to continue (yes/no)? 
```

Answer with `yes` and let it rebuild the authorized_keys file for consistency sake

* first run to setup the environment and read the `gitlab.rb` file

**NOTE** if you're running 6.9.2 like I was, due to a bug in the code you first need to edit a file to allow systemctl to be used in place of initctl in the `upstart.rb` file

```
vi /opt/gitlab/embedded/cookbooks/runit/recipes/upstart.rb
```

replace any `initctl` with `systemctl`

* now run the reconfigure task (fyi, this is just using [Chef](https://www.chef.io/chef/) to make sure everything is configured correctly... pretty cool way to verify the system is ready)

```
gitlab-ctl reconfigure
```

if it completes sucessfully, start GitLab

```
gitlab-ctl start
```

* You should be able to hit it via the URL, login as you would, do a git pull from a known working client (ie- it works against the old host)

* (optional) have Jenkins run a job that pulls code from the new GitLab

### Using RPM for further upgrades

Now we're going to start upgrading the GitLab code to bring it up to the current release, 10.2.2, and will switch to use RPM/yum to pull directly from the packagemanager instead of pulling individual RPMS

```
yum install pygpgme yum-utils
```

* create/edit a file named `/etc/yum.repos.d/gitlab_gitlab-ce.repo` that contains:

```
[gitlab_gitlab-ce]
name=gitlab_gitlab-ce
baseurl=https://packages.gitlab.com/gitlab/gitlab-ce/el/7/$basearch
repo_gpgcheck=1
gpgcheck=1
enabled=1
gpgkey=https://packages.gitlab.com/gitlab/gitlab-ce/gpgkey
       https://packages.gitlab.com/gitlab/gitlab-ce/gpgkey/gitlab-gitlab-ce-3D645A26AB9FBD22.pub.gpg
sslverify=1
sslcacert=/etc/pki/tls/certs/ca-bundle.crt
metadata_expire=300

[gitlab_gitlab-ce-source]
name=gitlab_gitlab-ce-source
baseurl=https://packages.gitlab.com/gitlab/gitlab-ce/el/7/SRPMS
repo_gpgcheck=1
gpgcheck=1
enabled=1
gpgkey=https://packages.gitlab.com/gitlab/gitlab-ce/gpgkey
       https://packages.gitlab.com/gitlab/gitlab-ce/gpgkey/gitlab-gitlab-ce-3D645A26AB9FBD22.pub.gpg
sslverify=1
sslcacert=/etc/pki/tls/certs/ca-bundle.crt
metadata_expire=300
```

* update your local yum cache by running

```
sudo yum -q makecache -y --disablerepo='*' --enablerepo='gitlab_gitlab-ce'
```

### Upgrade GitLab to 7.x

**NOTE** old GitLab packages weren't signed, so we need to skip that here)

```
yum install --nogpgcheck gitlab-ce-7.14.3-ce.0.el7.x86_64
```

**NOTE** output from the install script

```
# If you just upgraded from GitLab 7.9 or earlier, please run the following
gitlab: command:
gitlab:
gitlab: sudo ln -sf   /opt/gitlab/bin/gitlab-ctl   /opt/gitlab/bin/gitlab-rake   /opt/gitlab/bin/gitlab-rails   /opt/gitlab/bin/gitlab-ci-rake   /opt/gitlab/bin/gitlab-ci-rails  /usr/bin/
```

* follow the dirctions and undo the existing symlinks

```
ln -sf   /opt/gitlab/bin/gitlab-ctl   /opt/gitlab/bin/gitlab-rake   /opt/gitlab/bin/gitlab-rails   /opt/gitlab/bin/gitlab-ci-rake   /opt/gitlab/bin/gitlab-ci-rails  /usr/bin/
```

* run the reconfigure task, restart and make sure GitLab still works

```
gitlab-ctl reconfigure
gitlab-ctl restart
```

### Upgrade GitLab to 8.x

* install GitLab 8.x

**NOTE** again old GitLab packages weren't signed, skip that for this one

```
yum install --nogpgcheck  gitlab-ce-8.17.7-ce.0.el7.x86_64
```

**NOTE** follow the output and update postgres data

```
gitlab: GitLab now ships with a newer version of PostgreSQL (9.6.1), and will be used
gitlab: as the default in the next major release. To upgrade, RUN THE FOLLOWING COMMANDS:

gitlab-ctl pg-upgrade
```

* run that command, it will take a few minutes depending on the size of your database

```
gitlab-ctl pg-upgrade
```

### Upgrade GitLab to 9.x

```
yum install gitlab-ce-9.2.10-ce.0.el7.x86_64
```

* configure and restart

```
gitlab-ctl reconfigure
gitlab-ctl restart
```

### Upgrade to 10.x

Now we should be able to just install the latest by calling:

```
yum install gitlab-ce
```

* if you're using LDAP thee config to use lastest LDAP/SSL settings have changed, full config for `/etc/gitlab/gitlab.rb`

```
### base
external_url "https://domain-dev.com/"

### web
nginx['redirect_http_to_https'] = true
nginx['ssl_certificate'] = "/etc/gitlab/ssl/gitlab.crt"
nginx['ssl_certificate_key'] = "/etc/gitlab/ssl/gitlab.key"

### ldap
gitlab_rails['ldap_enabled'] = true
gitlab_rails['ldap_servers'] = YAML.load 
main:
  label: 'LDAP'
  host: 'ldap.domain.com'
  port: 636
  uid: 'sAMAccountName'

  bind_dn: 'CN=Service GitLab,OU=ServiceAccounts,OU=Users,OU=NonManaged,DC=domain,DC=com'
  password: '***********'

  encryption: 'simple_tls'
  verify_certificates: false
  ca_file: ''
  ssl_version: ''

  timeout: 10
  active_directory: true
  allow_username_or_email_login: true
  block_auto_created_users: false
  base: 'DC=domain,DC=com'

  user_filter: ''
  attributes:
    username: ['uid', 'userid', 'sAMAccountName']
    email:    ['mail', 'email', 'userPrincipalName']
    name:       'cn'
    first_name: 'givenName'
    last_name:  'sn'
EOS
```

* one more time, do the reconfigured/restart boogie

```
gitlab-ctl reconfigure
gitlab-ctl restart
```

* now test GitLab on DEV to and make sure everything works 

### Errata 

Optional steps if you're ready to go live

* stop GitLab on PROD

```
gitlab-ctl stop
```

* resync all repositories from PROD to DEV again in case any new work has been done. On PROD run

```
rsync -avz /var/git-data/repositories/ git@domain-dev.com:/var/git-data/repositories
```
 
* flip DNS so that the PROD hostname points to DEV (which will become the new PROD)

* edit gitlab url hostname in `/etc/gitlab/gitlab.rb` to reflect the current host

```
external_url "https://domain.com/"
```

* run reconfigure so GitLab picks up the change

```
gitlab-ctl reconfigure
```

* restart GitLab

```
gitlab-ctl start
```
