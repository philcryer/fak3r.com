---
title: "Ansible Speed Tweaks"
summary: "Speed up your Ansible runs with this modified ansible.cfg"
date: "2022-09-14T08:34:11-05:00"
Tags: ["ansible", "howto"]
Categories: ["automation"] 
---
<div align="right"><img src="/2022/Ansible_Logo.png" alt="Ansible"></div>

I've been a huge fan of [Ansible](https://ansible.com) for years, and am happy to be using it at work again. Doing some grunt work in debugging longer running processes led me to re-research tips to speed up playbook runs, and I've saved that effort here. Within your Ansible project, just add these lines to your ansible.cfg file (in the root directory of the project by default), and see how it goes. A good plan is to use the callbacks_enabled line to see how long individual roles are taking, plus a good indicator on how much this helps you.

Give a shout out to me on [Twitter](https://twitter.com/fak3r) with feedback or other tips you've figured out!

```
# ansible.cfg optimization ideas and config from:
#  - https://jpmens.net/2015/01/29/caching-facts-in-ansible/
#  - https://docs.ansible.com/ansible/latest/plugins/cache.html
#  - https://www.redhat.com/sysadmin/faster-ansible-playbook-execution
#  - https://www.linkedin.com/pulse/how-speed-up-ansible-playbooks-drastically-lionel-gurret
 
[defaults]
forks = 50
ask_pass = false
roles_path = roles
remote_user = dataiku
 
# fact checking cache
gathering = smart
fact_caching = jsonfile
fact_caching_timeout = 3600
fact_caching_connection = /tmp/ansi
 
# poll more frequently to improve response time 
# NOTE: could be too agressive for long running (ie- backup) processes
internal_poll_interval=0.001
 
# turn on for development and testing
#callbacks_enabled = timer, profile_tasks, profile_roles
 
# unused options
#inventory = inventory
#host_key_checking = false
 
[inventory]
# inventory cache
cache=True
 
[privilege_escalation]
become = false
become_ask_pass = false
 
[ssh_connection]
# ssh speedups
pipelining = True
control_path = /tmp/ansi/ansible-ssh-%%h-%%p-%%r
ssh_args = -o ControlMaster=auto -o ControlPersist=3600s -o PreferredAuthentications=publickey
```
