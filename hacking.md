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

## Package Repositories

The gravity-platform makes heavy use of multiple package repositories. The repositories being used
MUST be detailed in this guide.

The PHP package archive [packagist](https://packagist.org/) SHALL be used for code in the libgraviton
organisation.

Node packaged modules [npm](https://npmjs.org/) SHOULD only be used during the build phase of JavaScript based
projects.

Precompiled JavaScript components MUST be installed with [Bower components](http://sindresorhus.com/bower-components/)
before they MAY be compiled into a deliverable using tools from npm.

## Components

### Graviton

#### Services

##### Generating a Service

TODO: show how to use the generator

##### Service Anatomy

Each service in graviton-2 SHALL consist of the following files. The follwing example was taken from the ``/core/app`` service.

<table>
  <tr>
    <th>File/Directory</th>
    <th>Description</th>
  </tr>
  <tr>
    <td><code>src/Graviton/CoreBundle</code></td>
    <td>bundle directory, contains all the files related to a bundle</td>
  </tr>
  <tr>
    <td><code>src/Graviton/CoreBundle/Controller/AppController.php</code></td>
    <td>controller for ``/core/app`` service, extends RestController</td>
  </tr>
  <tr>
    <td><code>src/Graviton/CoreBundle/DataFixtures/MongoDB/LoadAppData.php</code></td>
    <td></td>
  </tr>
  <tr>
    <td><code>src/Graviton/CoreBundle/Document/App.php</code></td>
    <td></td>
  </tr>
  <tr>
    <td><code>src/Graviton/CoreBundle/Model/App.php</code></td>
    <td></td>
  </tr>
  <tr>
    <td><code>src/Graviton/CoreBundle/Repository/AppRepository.php</code></td>
    <td></td>
  </tr>
  <tr>
    <td><code>src/Graviton/CoreBundle/Resources/config/doctrine/App.mongodb.xml</code></td>
    <td></td>
  </tr>
  <tr>
    <td><code>src/Graviton/CoreBundle/Resources/config/schema/App.json</code></td>
    <td></td>
  </tr>
  <tr>
    <td><code>src/Graviton/CoreBundle/Resources/config/serializer/Document.App.xml</code></td>
    <td></td>
  </tr>
  <tr>
    <td><code>src/Graviton/CoreBundle/Tests/Controller/AppControllerTest.php</code></td>
    <td></td>
  </tr>
</table>

### Graviphoton
