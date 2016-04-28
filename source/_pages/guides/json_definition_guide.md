---
title: JSON Definitions
layout: default
top_nav: guides
---
# JSON Definition Guide

Graviton has supports a powerful way to generate many services using the json model from [json-schema](https://github.com/libgraviton/json-schema).

## Definitions and Bundles

It shares this model with internal components we use to load data into some graviton services and this is it''s main documentation.

JSON definitions usually get installed through bundles. The simplest case of such bundles being bundles that only contain some definitions.

For graviton to pick up json definitions they must reside in a `Resources/defintion` folder anywhere in the bundle.

<div class="alert alert-info">
    <h4>Info</h4>
    <p>We plan on creating something like a yeoman generator for new bundles or a an editor for them but no plans have been concretized yet</p>
</div>

## Inside a Definition

Each definition defines either a full services or subdocument parts of a service. For starters they must name and
describe the thing they are creating as well as ensuring it has a unique id.

```
{
  "id": "foo",
  "title": "Foo Resources",
  "description": "Foo resources get used at Acme Inc. to create their deliverables."
}
```

The remainder of the definition is split into two parts. The `service` subnode describes most of the aspects of how
each services gets rendered. The `target` entry describes how the underlying mongodb insrtance is to be set up.

```
{
  "service": {
    // ...
  },
  "target": {
    // ...
  }
}
```

We will be looking at each of those sections individually starting with the `service` section.

### Service

At the top level of a `service` some basic aspects of the service are set up. The `routerBase` defines where on the
service a service should be created. And the `readOnly` attribute defines if PUT/POST/DELETE routes for a service are
to be exposed.

```
{
  "service": {
    "routerBase": "/acme/foo",
    "readOnly": false
  }
}
```

The foo resource service from above shows up on http://example.com/acme/foo/ and exposes a fully editable service with
the above.

<div class="alert alert-info">
    <h4>Info</h4>
    <p>Creating read-only services is not (yet) covered by this guide.</p>
</div>

### Target

For the service to be usable it also needs to define some fields in the `target` entry.

```
{
  "target": {
    "fields": [
      // ...
    ]
  }
}
```

Since we want Acme Inc. to be able to categorize their foo resources we add an array of tags. So that Acme Inc. can use
their widgets in internationalized software we make sure that the title of the resource is translatable. We are also adding
an id field since we want to be able to access our resources and reference to them.

```
{
  "target": {
    "fields": [
      {
        "name": "id",
        "type": "varchar",
        "title": "Foo Stock ID",
        "description": "Unique stock idintifier for foo resources as needed by existing systems."
      },
      {
        "name": "title",
        "type": "varchar",
        "title": "Name of resource",
        "description": "Human readable name of foo resources."
        "translatable": true
      },
      {
        "name": "tags.0",
        "type": "varchar",
        "title": "Tags",
        "description": "Tags to easily categorize foo resources."
      }
    ]
  }
}
```

As you can see each of the fields have some distinct fields that need setting.

### Fields

After having introduced the field array it makes sense to look into how fields are defined.

For the most part the fields show above are what is required on all fields. The `name` tells
graviton how the field should be exposed in the resource. The `title` and `description` field
get exposed in our JSON-schemas and in our swagger spec.

The `type` specifies what type of field that graviton shall realize. It supports multiple options.

For a start the following values are all legal.

* `string`
* `varchar`
* `text`
* `int`
* `bigint`
* `float`
* `double`
* `decimal`
* `datetime`
* `boolean`
* `hash`
* `object`
* `extref`

For the most part they do what one would expect them to. Please refer to the JSON definition reference for more info.

For referencing other services the `type` field also supports referencing classes in the following forms.

* `class:AcmeNamespace\\AcmeBundle\\Document\\Bar`
* `class:AcmeNamespace\\AcmeBundle\\Document\\Bar[]`

### Special fields

There are some special cases of fields that need some more work. They are explained here.

#### Translatable fields

You can define fields as translatable. They will then get rendered in all the languages available in the system.

```
{
  "target": {
    "fields": [
      {
        "name": "translatableField",
        "type": "varchar",
        "title": "Title of translatableField",
        "description": "Description of translatableField in a sentence.",
        "translatable": true
      }
    ]
  }
}
```

Please keep in mind that for this to work the default language (English) always needs to be defined.

You only need to set `translatable` to `true` on fields you need it. The default is to not make fields
translatable.


#### Extref fields

Albeit there name, extref fields are used to reference other services in a graviton instance using
JSON-Reference.

```
{
  "target": {
    "fields": [
      {
        "name": "refField",
        "type": "extref",
        "title": "Title of $ref",
        "description": "Description of $ref in a sentence.",
        "exposeAs": "$ref",
        "collection": ["*"]
      }
    ]
  }
}
```

You should only use the `extref` type with `exposeAs` set to `$ref`. You can specifiy what target are
valid in a `$ref` field using the `collection` attribute. The above example allow links to anything, but
you can also specify allowed mongodb collection names.

```
{
  "target": {
    "fields": [
      {
        "collection": [
          "Bar"
        ]
      }
    ]
  }
}
```

#### Dynamic key fields

There are some cases where we need to expose a array for objects as a hash, using a field from the object as key for the resulting hash.

```
{
  "name": "bar",
  "type": "class:AcmeNamespace\\BarBundle\\Document\\Bar",
  "title": "bar resources",
  "description": "A hash containing some bar resources.",
  "x-dynamic-key": {
    "document-id": "AcmeNamespaceBarBundle:Bar",
    "repository-method": "findAll",
    "ref-field": "id"
  }
}
```

The above example uses the `id` field of the Bar document as key in hashes. It uses the `ref-field` value of the resources found by `findAll` on `AcmeNamespaceBarBundle:Bar`
as key and only allows those values as key.

#### Immutable fields in writable services

Graviton also  certain fields to be made readonly. This may be used to add additional fields to a service that may not be changed by a client. This can be used to decorate services with additional info generated on the server side. One example of such a service is the `/file` endpoint where some of the metadata is generated on the server and may not be overridden by the client (ie. `metadata.size`).

```
{
  "name": "bar",
  "type": "string",
  "title": "Bar",
  "description": "A horse walks into a bar...",
  "readOnly": true
```

### Relations

```
{
  "target": {
    "relations": [
      // ...
    ]
  }
}
```

Our schema allows you to specify relations between documents. Usually these relationships are added as type `class:...` field - 
the `relations` array of object allows you to specify what *type* of relation you want to generate if you want to override default behavior.

<div class="alert alert-info">
    <h4>Info</h4>
    <p>If you don't specify a <code>relations</code> entry for a given field, Graviton will generate a <code>reference</code> type of relation.
    Having said that, it is usually good to still specify the exact kind of relationship you wish to create.</p>
</div>

In order to force the generation of an `embed` relationship, you can notate: 

```
{
  "target": {
    "relations": [
      {
        "type": "embed",
        "localValueField": "myfield"
      }
    ]
  }
}
```

You may decide which relation type you want to have depending on your use case as they have different circumstances:

A *reference* relationship:

* Will save considerable amount of storage space in the database.
* Will not allow the user to issue `RQL` queries against fields in referenced objects.

An *embed* relationship

* Will duplicate the record every time the user saves it, resulting in more storage needs and leaving possibly abandoned copies of a document around.
* Will allow the user to issue `RQL` queries against fields in embedded objects.

To the end user, the rendered output will be identical. This property only influences the internal storage of your structures.

<div class="alert alert-warning">
    <h4>Warning</h4>
    <p>Note that you <strong>may not</strong> change relation types of existing services! This will abandon all previously saved objects
    that are saved using the <i>old</i> structure. There is no automatic migration in place. If you need migrations please refer to the
    <a href="/guides/mongodb_migration_guide">migration guide</a>.</p>
</div>
