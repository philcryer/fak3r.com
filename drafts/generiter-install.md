# Installing generiter

![]generiter-icon.jpg

## macOS

Generiter requires Python 3.6+ but older versions of macOS only had Python 2.7. First determine if you're running 3.x or 2.x: Open up the command line via the Terminal application which is located at Applications -> Utilities -> Terminal.

In the terminal, at the `$` prompt, enter the following command, and look at the output:

```
$ python --version
Python 2.7.17
```

If this is version 2.x, continue, if it's 3.x, skip down to 'Installing via pip'

### Installying Python 3.x

To update macOS's version of Python, we'll use the tool [Homebrew](brew.sh), to do this, cut and paste the following command into your terminal, and press 'Enter'

```
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

This will install Apple's Xcode commandline tools and other required software, it could take up to 5 minutes to complete. When this is done, initialize and test that `homebrew` is installed and working:

```
brew doctor
```

If that's successful, use `homebrew` to install Python 3.9:

```
brew install --build-from-source python@3.9
```

This command will take longer than the last one, as it's going to build Python from source.

### Installing generiter via pip

Now we'll use `pip`, a package manger for Python, to install generiter

```
pip3 install generiter
```

## Linux

Generiter requires Python 3.6+ which most recent version of Linux will have. First determine if you're running 3.x or 2.x: Open up terminal application in Linux, and prompt, enter the following command, and look at the output:

```
$ python --version
Python 2.7.17
```

If you see a version of Python starting with a 2, such as Python 2.7.10, then try the same command using python3 instead of python, it's possible both will be installed.

If it's not and you only have version 2.x, continue, if it's 3.x, skip down to 'Installing generiter via pip'

### GNU Debian Linux and Ubuntu Linux

Install Python 3.x as defined at [Install Python](https://installpython3.com/linux/), by using the deadsnakes PPA:

```
sudo add-apt-repository ppa:deadsnakes/ppa
sudo apt-get update
sudo apt install python3.8
```

### Arch Linux

Install Python 3.x via `pacman`:

```
pacman -S python3
```

### Installing generiter via pip

Now we'll use `pip`, a package manger for Python, to install generiter

```
pip3 install generiter
```

## Windows

Install Python 3.x ad defined at [Install Python](https://installpython3.com/windows/)

Open Powershell, and run the following command:

```
pip3 install generiter
```
