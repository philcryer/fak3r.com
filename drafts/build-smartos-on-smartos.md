imgadm import 4aec529c-55f9-11e3-868e-a37707fcbe86

smarts-build.json:

{
  "brand": "joyent",
  "alias": "smartos-build",
  "fs_allowed": "ufs,pcfs,tmpfs",
  "image_uuid": "4aec529c-55f9-11e3-868e-a37707fcbe86",
  "resolvers": ["8.8.8.8", "208.67.220.220"],
  "max_physical_memory": 10240,
  "quota": 40,
  "nics": [
  {
    "nic_tag": "admin",
    "ip": "192.168.1.230",
    "netmask": "255.255.255.0",
    "gateway": "192.168.1.1"
  }
 ]
}

vmadm create -f smarts-build.json

usermod -P 'Primary Administrator' admin

su - admin

pfexec pkgin in scmgit
git clone https://github.com/joyent/smartos-live
cd smartos-live
cp sample.configure.smartos configure.smartos
./configure

gmake live

gmake usb
