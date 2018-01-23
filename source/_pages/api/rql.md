---
title: RQL
layout: default
top_nav: api
---

# RQL

Resource Query Language (RQL) is the query language supported by all graviton-2 collection endpoints.

You can use RQL to query for specific documents and also to implement paging and sorting.

Please see the documentation linked in the Appendix for more details on RQL.

## Examples

* ``https://example.org/core/module/?eq(name,foo)`` - Return all entries where name == "foo"
* ``https://example.org/core/module/?limit(5)`` - Only return the first 5 items
* ``https://example.org/core/module/?limit(2,4)`` - Return 2 items, starting with item number 4
* ``https://example.org/core/module/?eq(name,foo)&limit(2)`` - Return the first two items where name == "foo"
* ``https://example.org/core/module/?name=foo`` - Return items where name == "foo" (alternative syntax)

## Special cases

### elemMatch operator

Use ``elemMatch`` operator to specify multiple criteria on the elements of an array such that at least one array element satisfies all the specified criteria.

The following example returns documents where the ``items`` array contains at least one element that has ``type = a`` and ``name = b``:

```
elemMatch(items,and(eq(type,a),eq(name,b)))
```

Since the ``elemMatch`` only specifies a single condition, the ``elemMatch`` expression is not necessary, and instead you can use the following query:

```
eq(items..type,a)
```

### search operator

A search operator has been implemented to help with searching in common fields across records. This is intended to ease clients searching capabilities and
needs to be enabled for individual services. Read [Indexes](/api/index-and-search) on how to define search DB fields.

```
search(foo.bar)
```
Result is sorted by SCORE, sum of words found by the weight assigned for each field.

### Limit

Due to the fact the [the specs](https://doc.apsstandard.org/2.1/spec/rql/) and the [reference implementation](https://github.com/persvr/rql) differ slightly we had to choose a variant.

Our implementation now follows the following call syntax:

``limit(start,count)`` - Return the given number of objects from the start position.

Basically we are doing what the reference implementation documents but removing
the ``maxCount`` arg.

### String encoding in parameters

Remember that strings passed in function parameters must be encoded in a special fashion in order to make sure that they don't escape the ``function()``.

Please refer to the [parser encoding rules](https://github.com/xiag-ag/rql-parser#encoding-rules) for further information.

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

To extract links from header you can use the following function, sample:
```php
$strHeaderLink = '<http://localhost/core/module/?limit(10%2C10)>; rel="next",<http://localhost/core/module/?limit(10%2C20)>; rel="last"';

function extractLinks($headerLinkString)
{
    preg_match_all('/<(.*?)>; rel="([^"]+)"/i', $headerLinkString, $matches);
    if (!array_key_exists(2, $matches)) {
        return [];
    }
    array_walk(
        array_map(function($link, $key) {
            return [$key => $link];
        }, $matches[1], $matches[2]),
        function($link) use (&$links) {
            $links[key($link)] = strtr(reset($link), ['%2C' => ',']);
        },
    $links = []);
    return $links;
}

// Result of print_r(extractLinks($strHeaderLink));
Array
(
    [next] => http://localhost/core/module/?limit(10,10)
    [last] => http://localhost/core/module/?limit(10,20)
)
```

## Appendix
### Resources

* [Original JS Implementation](https://github.com/persvr/rql)
* [PHP Parser](https://github.com/xiag-ag/rql-parser)
* [RQL Spec](https://doc.apsstandard.org/2.1/spec/rql/)
* [Encoding Rules](https://github.com/xiag-ag/rql-parser#encoding-rules)
