---
title: Developer
layout: default
top_nav: guides
---
# How to Hack the Gravity-Platform

Are you new to hacking on graviton and graviton based services? This document aims at
getting your local development infrastructure up and running.

It assumes that you have a general idea of how modern multitier architecture and tries
to contain enough information to help getting people from various it backgrounds started.

## Overview

Our gravity-platform services are written in a few different languages and are organized
into the following tiers.

- mongodb as data tier
- graviton, a symfony 2 based JSON/REST server generation toolkit as api tier
- several workers as logic tier
- various angular2 or mobile native presentation tiers that stay in-house for the most part

Along with several supporting services all these services make out what we consider a kind
of gravity platform for the sake of this documentation.

## Requirements

For you to start developing on most parts of the platform you will need a suitable workstation.

We recommend copiouos amounts of RAM as well as a fast SSD for being able to work effectivly.
You will probably get away with HDD based systems and 4 GB of RAM but you will certainly incur
a noticable performance penalty.

Due to the fact that all parts of the stack run on GNU/Linux at runtime, the single most efficient
way to develop with the platform is on a linux box. Using a virtual box is fine and actually
encouraged since it leads to a more stable development environment in all cases.

The most used cases used by platform developers at the moment are usually using
[VirtualBox](https://www.virtualbox.org/) on either OS X or Windows.

In theory it is also possible to run all the tools native. Using a modern GNU/Linux distribution
this is easy to set up and do, on OS X it is only for the brave (and maybe slightly mad) and
on Windows running natively is mostly likely for the insane. We don't remember anyone returning
from going down the last path.

## Where wild codes are

Large parts of our code are in the open and available through github. The following list points
out the key repositories and what they contain.

- [graviton rest server generator](https://github.com/libgraviton/graviton)
- [libs for building angular clients](https://github.com/graviphoton)
- [work base lib](https://github.com/libgraviton/graviton-worker-base-java)
- [these docs](https://github.com/gravity-platform/doc)

Have a look at the organizations the repos are in if you are looking for more bits and pieces.

## The commons

All our repos have some things in common. They all contain a README.md that explains what the
repository is about. The follow [git-flow](http://nvie.com/posts/a-successful-git-branching-model/)
conventions and contain the bare code without any build artefacts. We strive at keeping the code
at a deployable state and the repos usually contain automation to that effect. To aid in proper
communication about code we follow the [semver](http://semver.org/spec/v2.0.0.html) versioning
standard.

### git-flow

We use the [``git-flow``](http://nvie.com/git-model/) system for version control wherever possible.

If you want to use the model effectively it is highly recommend that you look into tooling to do so.
Regular git users may find  the [gitflow tool](https://github.com/nvie/gitflow) handy and there are
also implementations for many major platforms.

Further reading on ``git-flow`` may be found on the excellent
[``git-flow`` cheatsheet](http://danielkummer.github.io/git-flow-cheatsheet/).

We usually use the default branch prefixes given by ``git-flow`` except for the version tag prefix
which is ``v``. If you stray from this well lit path you should add documentation on the effect to
the repos readme.

### Semantic Versioning

It is very import for packages to not only be versioned but for these versions to
convey a clear and semantic meaning. This is why we follow the rules given in the [Semantic Versioning 2.0.0](http://semver.org/spec/v2.0.0.html)
specification.

Incrementing the version is done on a ``release`` branch after carefully considering
all the merges in ``develop`` or in ``hotfixes``.

## Package Repositories

The gravity-platform makes heavy use of multiple package repositories. The repositories being used
are detailed in this guide.

The PHP package archive [packagist](https://packagist.org/) is used for all open PHP code. Additionally there are some private bundle only available to select partners on our internal satis server.

Node packaged modules [npm](https://npmjs.org/) are used during the build phase of JavaScript based
projects.

Precompiled JavaScript components for the browser can be installed with [Bower components](http://sindresorhus.com/bower-components/) or with npm as required.
