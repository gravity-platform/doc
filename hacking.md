---
title: Developer Guide
layout: default
---
# How to Hack the Gravity-Platform

This document show how to hack the gravity-plattform in a most effectiv fashion.

> The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT",
> "SHOULD", "SHOULD NOT", "RECOMMENDED",  "MAY", and "OPTIONAL" in
> this document are to be interpreted as described in RFC 2119.

This document has a normative character and all developers MUST follow the
guidelines given.

## Code Repositories

The gravity-plattform SHALL be hosted on the following github organisations.

- [libgraviton](https://github.com/libgraviton)
- [graviphoton](https://github.com/graviphoton)
- [gravity-platform](https://github.com/gravity-platform)

## ``git-flow``

The gravity-platform SHALL use [``git-flow``](http://nvie.com/git-model/) for version control.
We make the OPTIONAL recommendation to use the [gitflow tool](https://github.com/nvie/gitflow).
Further reading on ``git-flow`` may be found on the excellent
[``git-flow`` cheatsheet](http://danielkummer.github.io/git-flow-cheatsheet/).

You MUST use the ``git-flow`` model on the aforementioned github organisations.
Is is RECOMMENDED that you also use ``git-flow`` on internal applications based on the gravity-platform.
You MUST use the default branch prefixes given by ``git-flow`` except for the version tag prefix 
which SHALL be ``v``. As an exception to the stated rule, the gravity-platform/doc git repository
MUST use the ``gh-pages`` branch as default.

## Semantic Versioning

It is very import for packages to not only be versioned but for these versions to
convey a clear and semantic meaning. Thus all packages in the aforementioned repositories
MUST follow the rules given in the [Semantic Versioning 2.0.0](http://semver.org/spec/v2.0.0.html)
specification.

Incrementing the version SHOULD be done on a ``release`` branch after carefully considering
all the merges in ``develop`` or in ``hotfixes``. On the ``develop`` branch the version number
SHOULD always point to the next MINOR version and be postfixed with ``-dev``.
