---
title: References
layout: default
top_nav: api
---

# Referencing Resources

In graviton based servers documents may be linked using [JSON-Reference](https://json-spec.readthedocs.org/en/latest/reference.html). Currently only links targeting documents are supported.

As per the specs, links are presented in a ``$ref`` attribute. Links in graviton services contain a fully qualified URL.

## ``$ref`` in Schemas

Given the following document that has a link to another document on the graviton server.

```json
{
  "$ref": "https://graviton.nova.scapp.io/core/app/test"
}
```

The following schema might be generated.

```json
{
  "title": "Example Resource",
  "type": "object",
  "properties": {
    "$ref": {
      "title": "Reference",
      "description": "Resource this link references.",
      "type": "string",
      "format": "extref",
      "x-collection": [
        "https://graviton.nova.scapp.io/core/app/"
      ]
    }
  }
}
```

The ``format`` key with a value of ``extref`` allows clients to recognize that a link points to a graviton service.

In such cases the ``x-collection`` attribute exposes a list of valid collections that may be used in links. In the above example the values from ``https://graviton.nova.scapp.io/core/app`` could be used to populate a list of possible links by a client.

The ``x-collection`` attribute also supports containing a value of ``*``. The wildcard value specifies that any valid graviton service may be used as a link target.

## Querying ``$ref`` Entries

While querying ``$ref`` entries, the usual [RQL encoding rules](https://github.com/xiag-ag/rql-parser#encoding-rules) have to be followed.

A query fetching the above example would look as follows.

```http
https://graviton.nova.scapp.io/service/?%24ref=https%3A%2F%2Fgraviton.nova.scapp.io%2Fcore%2Fapp%2Ftest
```

To fetch a list of entries to populate a select box you might combine this with the ``select()`` operator.

```http
https://graviton.nova.scapp.io/service/?%24ref=https%3A%2F%2Fgraviton.nova.scapp.io%2Fcore%2Fapp%2Ftest&select(id,name)
```

### Special Cases

Querying nested objects may use the ``.`` or the ``..`` operator. This depends on whether you are searching inside an array or an object.

Given the following resource.

```json
{
  "object": {
    "$ref": "https://graviton.nova.scapp.io/core/app/test"
  },
  "array": [
    {
      "$ref": "https://graviton.nova.scapp.io/core/app/test"
    }
  ]
}
```

You may use the following queries (note the operator before ``%24ref``).

```http
https://graviton.nova.scapp.io/service/?object.%24ref=https%3A%2F%2Fgraviton.nova.scapp.io%2Fcore%2Fapp%2Ftest
https://graviton.nova.scapp.io/service/?array..%24ref=https%3A%2F%2Fgraviton.nova.scapp.io%2Fcore%2Fapp%2Ftest
```

<div class="alert alert-info" markdown="1">

The special behaviour regarding the ``..`` operator and ``$ref`` is being tracked in [#253](https://github.com/libgraviton/graviton/issues/253) and is subject to change.

</div>
