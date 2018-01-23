---
title: Versions
layout: default
top_nav: api
---

# Packages Version

Every version of an installed package is returned in a header called `x-version`. Furthermore you're able to 
retrieve those by requesting `/core/version`. The schema is provided at `/schema/core/version`.
These versions show everything which may change the API. 

### Endpoint example

Since it's not a collection the endpoint does not have a slash at the end. 

````
GET /core/version -> returns an object containing all version numbers
````

Only GET requests are supported an return a response containing the following body.

```
{

    "versions": {
        "self": "v0.12.0",
        "graviton/graviton": "v0.31.0"
    }

}
```

A working example can be found [in the app cloud](https://graviton.scapp.io/core/version) along with [its schema](https://graviton.scapp.io/schema/core/version).

### self

If you want to display the wrapper/graviton version just add `self`. `self` is always the version of the context you're in.

* In graviton `self` refers to graviton itself
* In a graviton derived api it refers to the version of the graviton wrapper itself.
