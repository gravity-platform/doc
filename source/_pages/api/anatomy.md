---
title: Introduction
layout: default
top_nav: api
---
# The Graviton API

Most services on a graviton instance have a predictable API surface.

Each service comes in two parts, the schema declaration as well as the actual
API endpoints.

The examples below are based around the `/core/app/` services which usually exist
on all graviton instances.

Only a very small amount of graviton services do not follow this convention. In fact
the only currently exception should be the [version service](/api/versions) which
has its own documentation.

<div class="panel panel-info">
    <div class="panel-heading">
        <div class="panel-title">
          <span class="picto-info-round" aria-hidden="true"></span>
          More documentation is always available
        </div>
    </div>
    <div class="panel-body" markdown="1">
    Full documentation on all the available services is self hosted on each graviton instance.

    A basic service listing may be found at the root URL.
    ```bash
    curl https://example.com
    ```
    A more complete overview is available through a [swagger](http://swagger.io/) definition.
    ```bash
    curl https://example.com/swagger.json
    ```
    </div>
</div>


## Endpoints

### Schemas

All of our output has adequate schema documentation that may be used for
validation of graviton data structures. Much more important is that this schema
information allows us to add inline documentation on the semantics of the data
provided by graviton.

#### Schema describing an individual document

```bash
curl https://example.org/schema/core/app/item
```

#### Schema describing a collection of documents

```bash
curl https://example.org/schema/core/app/collection
```

Collection schemas are available as a convenience so you may use them against our
collection endpoints. For the most part they contain the same data you would also
find in the corresponding item schema.

### APIs

#### GET collection of documents

```bash
curl https://example.org/core/app/ 
```

You can search in collections using [RQL](/api/rql).

<div class="panel panel-info">
    <div class="panel-heading">
        <div class="panel-title">
          <span class="picto-info-round" aria-hidden="true"></span>
          Getting invalid data with RQL <code>select()</code> operator
        </div>
    </div>
    <div class="panel-body" markdown="1">
    Note that it is possible to export data that does not adhere to the schemas using the [RQL](/api/rql) `select()` operator.

    As a rule of thumb, if you plan on modifying retrieved documents and want to PUT them into graviton again, you
    should probably not use the operator.
    </div>
</div>


#### POST a new document (server defines id)

```bash
curl -X POST -d '{}' https://example.org/core/app/
```

Check the response headers to see what id your document was stored at.

#### PUT a new document or update an existing one (client defines id)

```bash
curl -X PUT -d '{"id": "my-id"}' https://example.org/core/app/my-id
```

This is also referred to as an *upsert* operation.

#### GET an existing document

```bash
curl -X GET https://example.org/core/app/my-id
```

#### DELETE a document

```bash
curl -X DELETE https://example.org/core/app/my-id
```

### Learn More

* [Document Lifecycle Events](/api/event)
* [The `/file/` endpoint](/api/file)
* [Internationalization](/api/i18n)
