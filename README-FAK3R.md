[![Netlify Status](https://api.netlify.com/api/v1/badges/2a5caefe-fdd1-4093-ac55-f0dc106b8c69/deploy-status)](https://app.netlify.com/sites/wizardly-liskov-e4f96e/deploys)

# fak3r.com

## Summary

This repository contains the source code for [fak3r.com](https://fak3r.com), which has been my blog since 2005, and it's currently built by [Astro](http://astro.build/), 
and hosted on an [Alpine Linux](https://www.alpinelinux.org/) host running on [Vultr](https://www.vultr.com/).

## Contents

The code and framework of posts, pages, plugins, theme and configuration used to create the site.

## Clone

Want to build on what I have? Go for it, it's how I got where I am. To get started, either fork it, or clone it from the command line.

* Clone the project

```
git clone https://github.com/philcryer/fak3r.com.git
```

* Rename the project as you'd like

```
mv fak3r.com new-name
```

* Clean out my pages, posts, graphics and drafts:

```
cd new-name
rm -rf src/content/blog/*
```

* Edit the config file to match your values:

```
vi astro.config.ts
```

* Add the contents to your project:

```
git add .

```

* Check it in under your account:

```
git commit -m "initial commit"
git push
```

...and you should be all set. Hit up the [Astro docs](https://docs.astro.build/) to learn how things work, or feel free to contact me with any questions.

## Commands

All `npm` commands can be run by the included [Makefile](Makefile), just ensure you have `make' installed (ie- apt intall make) and type 'make' to get an overview.

| Command                   | Action                                           |
| :------------------------ | :----------------------------------------------- |
| `make install`             | Installs all npm dependencies                            |
| `make dev`             | Starts local dev server at `localhost:4321`      |
| `make build`           | Build production site to `./dist/`          |
| `make build-verbose`           | Build production site to `./dist/` with verbose logging         |
| `make prod`           | Build production site, using prettier, to `./dist/` and deploy it to remote site      |
| `make deploy`           | Deploy existing build in `./dist/` to remote site       |

## FAQ

Q: What does fak3r mean?

A: My name is Phil, so a friend used to call me flip, so I started using name flipper online. That name was always taken so I shifted to flipp3r, but I didn't like that. Around that time I was getting into infosec and thinking about privacy online and the fallacy that a 'fake' online identity could provide true anonymity - that led to `fak3r`.

## Contact

email me at admin {at} fak3r {dot} com or ping me on Mastodon where I'm [@fak3r](https://mastodon.social/@fak3r)

## License

MIT License

Copyright (c) 2026 Phil Cryer // fak3r

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

### Thanks
