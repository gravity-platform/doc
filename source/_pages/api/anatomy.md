---
title: Anatomy
layout: default
top_nav: api
---
# Service Anatomy

This document describes the protocol between the graviton backend and
the graviphoton frontend.

> The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT",
> "SHOULD", "SHOULD NOT", "RECOMMENDED",  "MAY", and "OPTIONAL" in
> this document are to be interpreted as described in RFC 2119.

This document describes a domain specific, [HATEOAS](http://en.wikipedia.org/wiki/HATEOAS)
compliant [RESTful](http://en.wikipedia.org/wiki/Representational_State_Transfer) interface.
It has a normative character and is complemented by the api docs of the interface.

## service anatomy

The following section applies to each individual service hosted on the backend.

Each service provided by the backend MUST offer two different types of endpoints.
A collection endpoint as well a an item endpoint. The collection endpoint SHALL
be used for accessing lists of resources and for creating new resources. The item
endpoint SHALL be used for accessing, altering and deleting single resources.

The following URL examples show different types of operations that the backend MUST
support. All APIs will support these operations, the ``/core/app`` API is merely
used as an example due to it's early availability.

### collection endpoint examples
````
GET /core/app -> returns list of apps
POST /core/app -> create new app
````
### item endpoint examples
````
GET /core/app/hello -> returns hello world app
PUT /core/app/hello -> replace hello world app
PATCH /core/app/hello -> alter hello world app using json-patch
DELETE /core/app/hello -> delete app
````
