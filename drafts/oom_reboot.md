reboot server on out-of-memory condition

/etc/sysctl.conf

vm.panic_on_oom=1
kernel.panic=10
The vm.panic_on_oom=1 line enables panic on OOM, the kernel.panic=10 line tells the kernel to reboot ten seconds after panicking.
