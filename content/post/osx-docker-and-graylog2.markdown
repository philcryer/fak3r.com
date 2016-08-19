---
title: "Use Graylog2 with Docker to debug logs"
slug: "howto-install-chef-and-vagrant-on-10-9.markdown"
date: "2013-10-28T14:21:00-06:00"
categories:
- howto
- geek




date = "2016-04-26T11:24:07-05:00"
description = "How to bias an ASL tube amplifier"
keywords = ["stereo", "tube amps", "asl"]
title = "HOWTO Bias ASL Tube Amps"


---
<p>

# install docker

git clone https://github.com/Hi-Media/docker-graylog2.git
cd docker-graylog2

o$ docker pull arcus/kibana
$ docker pull himedia/elasticsearch
$ docker pull himedia/graylog2

$ docker build -t="himedia/graylog2" github.com/Hi-Media/docker-elasticsearch
$ docker build -t="himedia/graylog2" github.com/Hi-Media/docker-graylog2


$ ./graylog2-kibana-run.sh

