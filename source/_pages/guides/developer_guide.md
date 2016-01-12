---
title: Developer
layout: default
top_nav: guides
outdated: true
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

The gravity-platform SHALL use [``git-flow``](http://nvie.com/git-model/) for version control.
We make the OPTIONAL recommendation to use the [gitflow tool](https://github.com/nvie/gitflow).
Further reading on ``git-flow`` may be found on the excellent
[``git-flow`` cheatsheet](http://danielkummer.github.io/git-flow-cheatsheet/).

You MUST use the ``git-flow`` model on the aforementioned github organisations.
Is is RECOMMENDED that you also use ``git-flow`` on internal applications based on the gravity-platform.
You MUST use the default branch prefixes given by ``git-flow`` except for the version tag prefix
which SHALL be ``v``. As an exception to the stated rule, the gravity-platform/doc git repository
MUST use the ``gh-pages`` branch as default.

### Semantic Versioning

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

The PHP package archive [packagist](https://packagist.org/) SHALL be used for all PHP code.

Node packaged modules [npm](https://npmjs.org/) SHOULD only be used during the build phase of JavaScript based
projects.

Precompiled JavaScript components MUST be installed with [Bower components](http://sindresorhus.com/bower-components/)
before they MAY be compiled into a deliverable using tools from npm.

## Components

### Graviton

#### Services

##### Generating a Service

The service generator MAY be called as follows.

````
php app/console graviton:generate:resource --entity=GravitonFooBundle:Bar --format=xml --fields="name:string" --with-repository --no-interaction
````

Please refer to ``php app/console generate:doctrine:entities --help`` for further usage information.

After generating a new service you MUST review the code before committing it proper.

##### Service Anatomy

Each graviton service SHALL consist of the following files. The following example was taken from the ``/core/app`` service.

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
    <td>controller for <code>/core/app</code> service, MUST extend <code>Graviton\RestBundle\Controller\RestController</code></td>
  </tr>
  <tr>
    <td><code>src/Graviton/CoreBundle/DataFixtures/MongoDB/LoadAppData.php</code></td>
    <td>fixture loader, loads fixtures on initial install and during testing, MUST implement <code>Doctrine\Common\DataFixtures\FixtureInterface</code></td>
  </tr>
  <tr>
    <td><code>src/Graviton/CoreBundle/Document/App.php</code></td>
    <td>service document, access a single instance of an item, SHOULD implement <code>Graviton\I18nBundle\Document\TranslatableDocumentInterface</code></td>
  </tr>
  <tr>
    <td><code>src/Graviton/CoreBundle/Model/App.php</code></td>
    <td>service mode, wrapper around document and repository, adds schema information for a service, MUST extend <code>Graviton\RestBundle\Model\DocumentModel</code></td>
  </tr>
  <tr>
    <td><code>src/Graviton/CoreBundle/Repository/AppRepository.php</code></td>
    <td>service repository, access collections of documents, MUST extend <code>Doctrine\ODM\MongoDB\DocumentRepository</code></td>
  </tr>
  <tr>
    <td><code>src/Graviton/CoreBundle/Resources/config/doctrine/App.mongodb.xml</code></td>
    <td>MongoDB config, defines how data is persisted</td>
  </tr>
  <tr>
    <td><code>src/Graviton/CoreBundle/Resources/config/schema/App.json</code></td>
    <td>model config, schema information for the service</td>
  </tr>
  <tr>
    <td><code>src/Graviton/CoreBundle/Resources/config/serializer/Document.App.xml</code></td>
    <td>serializer config, defines how data is serialized and deserialized to the client</td>
  </tr>
  <tr>
    <td><code>src/Graviton/CoreBundle/Resources/config/services.xml</code></td>
    <td><abbr title="Dependency Injection Container">DIC</abbr> configuration, define services for classes needed by the service</td>
  </tr>
  <tr>
    <td><code>src/Graviton/CoreBundle/Resources/config/validation.xml</code></td>
    <td>Validation constraints for service data</td>
  </tr>
  <tr>
    <td><code>src/Graviton/CoreBundle/Tests/Controller/AppControllerTest.php</code></td>
    <td>service tests, e2e tests that make later refactoring possible, MUST extend <code>Graviton\TestBundle\Test\RestTestCase</code></td>
  </tr>
</table>

You MAY look at [CoreBundles code](https://github.com/libgraviton/graviton/tree/develop/src/Graviton/CoreBundle) to see  this in action.

If you want to manually create a service you MUST implement all the above elements. It is RECOMMENDED that you use the aformentioned ``graviton:generate:resource`` for all you service creation needs.

### Graviphoton
