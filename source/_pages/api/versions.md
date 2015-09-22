---
title: Versions
layout: default
top_nav: api
---

# Packages Version

Every version of an installed package is returned in a header called x-version. Furthermore you're able to 
retrieve those by requesting /core/version/. The schema is provided at /schema/core/version

### collection endpoint examples
````
GET /core/version -> returns object containing all version numbers
````

Other HTTP verbs aren't supported. 

## How to configure which version are reported

In the folder `app/config/` you can find a file called `version_service.yml` where you can add/remove packages.

If you want to display the wrapper/graviton version just add `self`

## How does it work?

Version numbers are read in the bootstrapping process and saved into the container using a compiler pass.

The version numbers are accessible trough the container as `graviton.core.version.data`.

For example: `$container->getParameter('graviton.core.version.data');`

CoreUtils afterwards handles the logic. 

