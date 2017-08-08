---
title: Versioning
layout: default
top_nav: api
---

# Document Versioning

To track the latest changes of a Document the definition must be set `versioning: true`.
While a document resource is set to be `versioning controlled` graviton will automatically update on each patch or put
the version of the document. If a request is sent without the corresponding version an exception will be thrown and
header x-current-version will tell you about the version the document is.

The schema will show a new field `version` with current document change version number. On creation this field is not 
required but can be sent as 0. After first creation the version will aquire `version: 1`. On updating this document 
with a PUT version field must match origin and so for Patch, required inside same request.

### Test case

A test case was created having a simple sample of the functionality.
[test](https://github.com/libgraviton/graviton/blob/develop/src/Graviton/CoreBundle/Tests/Controller/VersioningDocumentsTest.php)

The field is auto generated based on the definition:
````
 ...
 "service": {
    "readOnly": false,
    "versioning": true,
 ...
````

Schema definition:
````
GET /schema/testcase/versioning-entity/collection 
````

```
"version": {
    "title": "Version",
    "description": "Document version. You need to send current version if you want to update.",
    "type": "integer",
    "x-constraints": [
        "versioning"
    ]
}
```

