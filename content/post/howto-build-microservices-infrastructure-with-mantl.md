+++
title = "HOWTO build microservices infrastructure with Mantl"
Description = "Mantl is a platform for rapidly deploying a global distributed infrastructure"
date = "2015-05-27T18:41:06-06:00"
Categories = ["howto"]
Tags = ["mantl", "terraform", "ansible", "aws", "docker"]

+++

# Overview

I've been a while since the [microservices-infructure](https://github.com/ciscocloud/microservices-infrastructure) started, and an impressive project designed to get a microservices infrastructure setup with a reasonable set of defaults and No they seem to be getting more serious about the project by renaming the project...

<div align="center"><img src="/2015/mantl-logo-1.png" border="0" alt="Mantl"></div>

[Mantl](http://mantl.io) describes itself as, "A container orchestrator, docker, a network stack, something to pool your logs, something to monitor health, a sprinkle of service discovery and some automation." This is all well and good, but just like my project stax, there's lots to do up front. Let's get it rolling on [AWS](http://aws.amazon.com), but note that it can also be run on Vagrant, Openstack, Google Compute Engine, as well as bare metal, via [Terraform](https://terraform.io/).

## Getting started

* install required apps to install the code

```
apt-get update; apt-get install -y git curl unzip python-pip python-crypto-dbg
```

* terraform

```
cd ~; mkdir bin; cd bin 
wget https://dl.bintray.com/mitchellh/terraform/terraform_0.6.3_linux_amd64.zip
unzip terraform_0.6.3_linux_amd64.zip

echo "export PATH=$PATH:$HOME/bin" >> .profile ; source .profile
```

## Get the code

```
cd ~; git clone https://github.com/CiscoCloud/microservices-infrastructure.git;
cd microservices-infrastructure/
```

* use pip to install markup safe, which ansible needs

```
pip install markupsafe
```

* install ansible and other required python apps needed by the code

```
pip install -r requirements.txt
```

## Configure the project

* copy in aws sample file

```
cp terraform/aws.sample.tf aws.tf
```

* edit file, filling in access_key, secret_key and region from aws console

```
provider "aws" {
  access_key = "***REMOVED***"
  secret_key = "***REMOVED***"
  region = "us-east-1"
}

module "aws-dc" {
  source = "./terraform/aws"
  availability_zone = "us-east-1e"
  control_type = "t2.small"
  worker_type = "t2.small"
  ssh_username = "centos"
  source_ami = "ami-96a818fe"
  control_count = 3
  worker_count = 3
}
```

## Setup AWS permssions and access control

* create an IAM user in aws console

* create a IAM policy in aws console, adding the following

```
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Sid": "Stmt1433450536000",
      "Effect": "Allow",
      "Action": [
        "ec2:AttachInternetGateway",
        "ec2:AttachVolume",
        "ec2:AuthorizeSecurityGroupIngress",
        "ec2:CreateInternetGateway",
        "ec2:CreateRoute",
        "ec2:CreateRouteTable",
        "ec2:CreateSecurityGroup",
        "ec2:CreateSubnet",
        "ec2:CreateTags",
        "ec2:CreateVolume",
        "ec2:CreateVpc",
        "ec2:DeleteInternetGateway",
        "ec2:DeleteKeyPair",
        "ec2:DeleteRouteTable",
        "ec2:DeleteSecurityGroup",
        "ec2:DeleteSubnet",
        "ec2:DeleteVolume",
        "ec2:DeleteVpc",
        "ec2:DescribeImages",
        "ec2:DescribeInstances",
        "ec2:DescribeInternetGateways",
        "ec2:DescribeKeyPairs",
        "ec2:DescribeNetworkAcls",
        "ec2:DescribeRouteTables",
        "ec2:DescribeSecurityGroups",
        "ec2:DescribeSubnets",
        "ec2:DescribeVolumes",
        "ec2:DescribeVpcAttribute",
        "ec2:DescribeVpcs",
        "ec2:DetachInternetGateway",
        "ec2:DetachVolume",
        "ec2:ImportKeyPair",
        "ec2:ModifyInstanceAttribute",
        "ec2:ModifyVpcAttribute",
        "ec2:ReplaceRouteTableAssociation",
        "ec2:RevokeSecurityGroupEgress",
        "ec2:RunInstances",
        "ec2:TerminateInstances"
      ],
      "Resource": [
        "*"
      ]
    }
  ]
}
```

* assign that policy to the user

## Setup security in Mantl

* provide new admin password when prompted

```
./security-setup
```

* create SSH key

```
ssh-keygen -b 2048 -f ~/.ssh/id_rsa -P ''
```

## Install hosts with terraform

```
terraform get
terraform apply
```

* now look at the hosts with ansible

```
ansible all -i plugins/inventory/terraform.py -m ping
```

* if they answer 'pong' they’re all good

## Configure the new instance definitions

```
cp terraform.sample.yml terraform.yml
```

* edit it (comment out consul_acl_datacenter: if you only have one datacenter)

```
vi terraform.yml
```

## Run ansible using the yml file

```
ansible-playbook -i plugins/inventory/terraform.py -e @security.yml terraform.yml
```

(this will take some time, usually ~30 minutes)

## Login to Marathon

* in a web browser, attach to the Marathon node, logging in with the creditials you entered above.

## Launch some docker instnaces on the cluster

# Conclusion

The Mantl project feels very 
