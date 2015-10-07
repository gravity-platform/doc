---
title: Versions
layout: default
top_nav: api
---

# Packages Version

Every version of an installed package is returned in a header called x-version. Furthermore you're able to 
retrieve those by requesting /core/version. The schema is provided at /schema/core/version.
These versions show everything which may change the API. 

### Endpoint example

Since it's not a collection the endpoint does NOT have a slash at the end. 

````
GET /core/version -> returns an object containing all version numbers
````

Only GET requests are supported. 

The format will be something like this:

```
{

    "versions": 

    {
        "self": "v0.12.0",
        "graviton/graviton": "0.31.0"
    }

}
```

A working example can be found [here](https://graviton.nova.scapp.io/core/version).

And the schema [here](https://graviton.nova.scapp.io/schema/core/version).

## How to configure which version are reported

In the folder `app/config/` you can find a file called `version_service.yml` where you can add/remove packages.

### An example for `version_service.yml`

```
desiredVersions:

  - self
  
  - graviton/graviton
```

### self

If you want to display the wrapper/graviton version just add `self`. `self` is always the version of the context you're in.
This means if it's only a plain graviton instance `self` refers to the version of graviton itself. Moreover if it's for example 
a wrapper `self` refers to the version of the wrapper. In this case the graviton version will be referenced as graviton/graviton.
You can say `self` is the version number of the instance currently running.

