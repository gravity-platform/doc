---
title: Versions
layout: default
top_nav: api
---

# Packages Version

Every version of an installed package is returned in header called x-version. Furthermore you're able to 
retrieve those by requesting /core/version/

### collection endpoint examples
````
GET /core/version -> returns object containing all version numbers
````

Other HTTP verbs aren't supported. 

## Which version numbers are shown?
 
Generally every version number of a package which is prefixed with grv is shown.
This means every service bundle's version.
 
Additionally the version of the wrapper or graviton itself will be reported as self. 