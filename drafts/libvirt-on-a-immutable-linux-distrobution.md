

Running libvirt on an immutable Linux distrobution

I've been running a few different immutable Linux distrobutions, and I'm pretty much sold; this is the way of the future in my mind. In terms of security, reproducable, stable systems... there are just too many advantages to ignore. Looking back, 

https://universal-blue.org/

https://projectbluefin.io/



https://libvirt.org/


```
sudo apt install distrobox
```


To run libvirt/qemu/kvm we need a systemd container and we need a rootful container to be able to use it, see this tip to have a list of compatible images. We will use in this example AlmaLinux 8:



Distrobox now allows users to unshare certain locations on your filesystem. In its default mode the following shares are created, devsysfs, ipc, netns, process, $HOME and Application access. 

distrobox create --root --image docker.io/debian:12 --name debian-libvirt --init --unshare-all -Y
#--additional-packages "systemd podman libvirt"

distrobox enter --root debian-12


apt -y install qemu-kvm libvirt-daemon  bridge-utils virtinst libvirt-daemon-system


echo "ListenAddress 127.0.0.1
Port 2222" | sudo tee -a /etc/ssh/sshd_config

#####################
# distrobox-create --root --init --image quay.io/almalinux/8-init:8 --name libvirtd-container
# distrobox-enter --root libvirtd-container
#
#Let it initialize, then we can install all the packages we need:

:~> distrobox enter --root libvirtd-container
:~$ # We're now inside the container
:~$ sudo dnf groupinstall "Virtualization Host" --allowerasing -y
...
:~$ sudo systemctl enable --now libvirtd

Now we need to allow host to connect to the guest's libvirt session, we will use ssh for it:

:~$ # We're now inside the container
:~$ sudo dnf install openssh-server -y
:-$ echo "ListenAddress 127.0.0.1
Port 2222" | sudo tee -a /etc/ssh/sshd_config
:-$ sudo systemctl enable --now sshd
:-$ sudo systemctl restart sshd

#####################


Now we'll install VirtManager UI - you could do that in yet another distrobox, but I'm just going to install it on the main system, as a [flatpak](https://flatpak.org/) from [flathub](https://flathub.org/). This is as easy as:

```
flatpak install flathub org.virt_manager.virt-viewer
```

And then we'll start it up via the commandline:

```
flatpak run org.virt_manager.virt-viewer
```

Otherwise if you search for it on your desktop you'll want to launch 'Remote Viewer'. Unsure about the name change, but it's fine. 


