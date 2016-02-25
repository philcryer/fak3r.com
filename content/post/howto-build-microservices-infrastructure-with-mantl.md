+++
title = "HOWTO build microservices infrastructure with Mantl"
Description = "Mantl is a platform for rapidly deploying a global distributed infrastructure"
date = "2015-05-27T18:41:06-06:00"
Categories = ["howto"]
Tags = ["mantl", "terraform", "ansible", "aws", "docker"]

+++

# Overview


I've been watching [ciscocloud/microservices-infrastructure](https://github.com/ciscocloud/microservices-infrastructure) for awhile, an ambitious project designed to get a microservices infrastructure setup with a reasonable set of defaults. Now they seem to be getting more serious about the project and have renamed it [mantl](http://mantl.io/), which they define as, "_A container orchestrator, docker, a network stack, something to pool your logs, something to monitor health, a sprinkle of service discovery and some automation_". This sounds amazing, and certainly similar to something I did/try to do with my [stax](https://github.com/philcryer/stax) project... but just like with stax, there's lots to do up front. Let's give it a go. Will run it on [AWS](http://aws.amazon.com), but note that it can also be run on Vagrant, Openstack, Google Compute Engine, as well as bare metal, via [Terraform](https://terraform.io/). As usual I'm working from <a href="https://debian.org">Debian</a>, so if you're in something else, or OSX, your initial setup will vary.

<!--more-->

<div align="center"><img src="/2015/mantl-logo-1.png" border="0" alt="Mantl"></div>

## Getting started

### Installing required software

* install required apps we need to work with the code

```
apt-get update; apt-get install -y git curl unzip python-pip python-crypto-dbg
```

* then use `pip` to install terraform and markupsafe, which ansible needs

```
pip install ansible markupsafe
```

### Installing and configuring mantl

```
git clone https://github.com/CiscoCloud/mantl
cd mantl
```

* from the project install ansible and other required python apps needed by the project

```
pip install -r requirements.txt
``` 

* copy the aws sample file

```
cp terraform/aws.sample.tf aws.tf
```

* and edit it to include your details for the _access_key_, _secret_key_ and _region_ from aws console

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

### In AWS setup permssions and access control

* create an IAM user in aws console and assign the following

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

* setup security by running mantl's setup script

```
./security-setup
```

__NOTE__ provide new admin password when prompted

* create an SSH key

```
ssh-keygen -b 2048 -f ~/.ssh/id_rsa -P ''
```

* install hosts with terraform

```
terraform get
terraform apply
```

* now look at the hosts with ansible

```
ansible all -i plugins/inventory/terraform.py -m ping
```

* if they answer 'pong' they’re all good

* configure terraform

```
cp terraform.sample.yml terraform.yml
```

* edit the new config (comment out consul_acl_datacenter: if you only have one datacenter)

```
vi terraform.yml
```

* run it with that file

```
ansible-playbook -i plugins/inventory/terraform.py -e @security.yml terraform.yml
``` 

__NOTE__ this things take time (about 30 minutes in my tests)

### Login to Marathon

* now that we have things running, attach to the Marathon node, logging in with the creditials you entered above
* go through all the options
* launch some docker instanaces on the cluster
* etc

# Conclusion

The Mantl project feels very well thought out, and once you have it up and running you can start to understand how all the bits work together. I think this is a far better way than trying to reinvent the wheel youself and have to deal with the new shinny apps out there that just don't seem ready for primetime, or at least not mature enough to play well with others. I'm going to try and get this running within [Vagrant](https://www.vagrantup.com/) and will report back if that's successful on my laptop.
