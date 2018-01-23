---
title: Services
layout: default
top_nav: guides
outdated: true
---
# Creating Graviton Services

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
