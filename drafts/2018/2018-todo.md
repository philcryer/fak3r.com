1) how to hang a picture

2) looking forward, looking back

3) basic rest smoketest for ansible

lnxvmpccryerd01 role-smoketest (master)
└─ $ tree
.
├── defaults
│   └── main.yml
├── handlers
│   └── main.yml
├── meta
│   └── main.yml
├── README.md
└── tasks
    └── main.yml

4 directories, 5 files


-- 
└─ $ cat defaults/main.yml
---
#smoke_host: "{{ ansible_hostname }}"
smoke_host: "{{ ansible_fqdn }}"
#smoke_domain: "smrcy.com"
smoke_path: "/"
#smoke_port: "80"
smoke_status: "200"
smoke_content: "Copyright"


└─ $ cat handlers/main.yml
---
- name: "SMOKE get https webpage"
  environment:
    no_proxy: "{{ inventory_hostname }}"
  uri:
    url: "https://{{ smoke_host }}/{{ smoke_path }}"
    method: GET
    return_content: yes
    follow_redirects: all
    status_code: "{{ smoke_status }}"
    body_format: raw
    validate_certs: no
  register: webpage
  retries: 5
  delay: 15
  listen: "smoke"

#- debug:
#    msg: "webpage data is: {{ webpage.content }}"
#  listen: "smoke"

- name: "SMOKE verify web page serves real content"
  assert:
    that:
      - "'{{ smoke_content }}' in webpage.content"
  listen: "smoke"


lnxvmpccryerd01 role-smoketest (master)
└─ $ cat tasks/main.yml
---
- name:  "SMOKE notify the smoke tests to run"
  debug:
    msg: "SMOKE triggering smoke tests"
  notify: "smoke"
  changed_when: true


└─ $ cat meta/main.yml
---
galaxy_info:
  author: Phil Cryer
  description: Basic smoke test playbook to run after a deploy
  company: MTS
  license: CC-BY
  min_ansible_version: 1.2
  platforms:
  - name: EL
    versions:
    - 6
    - 7
  - name: Ubuntu
    versions:
    - all
  - name: Debian
    versions:
    - all
dependencies: []
