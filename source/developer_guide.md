---
title: Developer Guide
layout: default
---
# How to Hack the Gravity-Platform

This document show how to hack the gravity-platform in a most effectiv fashion.

> The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT",
> "SHOULD", "SHOULD NOT", "RECOMMENDED",  "MAY", and "OPTIONAL" in
> this document are to be interpreted as described in RFC 2119.

This document has a normative character and all developers MUST follow the
guidelines given.

## Code Repositories

The gravity-platform SHALL be hosted on the following github organisations.

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