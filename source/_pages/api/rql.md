---
title: File
layout: default
top_nav: api
---

# RQL

Resource Query Language (RQL) is the query language supported by all graviton-2 collection endpoints.

You can use RQL to query for specific documents and also to implement paging and sorting.

Please see the documentation linked in the Appendix for more details on RQL.

## Examples

* ``http://graviton.nova.scapp.io/core/module?eq(name,foo)`` - Return all entries where name == "foo"
* ``http://graviton.nova.scapp.io/core/module?limit(5)`` - Only return the first 5 items
* ``http://graviton.nova.scapp.io/core/module?limit(2,4)`` - Return 4 items, starting at item 2
* ``http://graviton.nova.scapp.io/core/module?eq(name,foo)&limit(2)`` - Return the first two items where name == "foo"
* ``http://graviton.nova.scapp.io/core/module?name=foo`` - Return items where name == "foo" (alternative syntax)

## Special cases

### Encoding of RQL in Link Headers

In an attempt to make ``Link`` headers parseable we are encoding parts of the actual header specially. Due to the fact that lots of clients take the header value and split it into individual chunks by simply splitting it at each ``,`` char we are encoding commas as ``%2C`` when they occur in an actual link.

This means you have to take some care when decoding a Link headers. Please refer to the following example for how this can be achieved.

```php
$links = array_map(
    function ($link) {
        return strtr($link, ['%2C' => ',']);
    },
    explode(',', $linkHeader)
);
```

## Appendix
### Resources

* [Original JS Implementation](https://github.com/persvr/rql)
* [PHP Parser](https://github.com/xiag-ag/rql-parser)
* [RQL Spec](https://doc.apsstandard.org/2.1/spec/rql/)
