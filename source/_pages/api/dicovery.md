---
title: Discovery
layout: default
top_nav: api
outdated: true
---
# Service Discovery

This document describes the protocol between the graviton backend and
the graviphoton frontend.

> The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT",
> "SHOULD", "SHOULD NOT", "RECOMMENDED",  "MAY", and "OPTIONAL" in
> this document are to be interpreted as described in RFC 2119.

This document describes a domain specific, [HATEOAS](http://en.wikipedia.org/wiki/HATEOAS)
compliant [RESTful](http://en.wikipedia.org/wiki/Representational_State_Transfer) interface.
It has a normative character and is complemented by the api docs of the interface.

## frontend discovers backend

The frontend MUST check for Link headers on the root of the server
it is hosted on. Non web based frontends SHOULD use a server that
is configurable in their settings. They SHALL NOT contain hard coded
references to a given backend server.

A HEAD request to / MUST return a Link header with a rel value of
"meta" pointing to a list of app resources. The type of the link
SHALL be "application/vnd.graviton.app+json".

The frontend MUST specifiy its version in the requests X-Version
header.
The backend MUST return a X-Version header specifying the version of the
installed backend server. It is REQUIRED that the frontend displays
this version along with its own version in a human readable format.
Both versions MUST adhere to the
[Semantic Versioning 2.0.0](http://semver.org) standard.

The backend MAY return an X-Name header containing a name of the
installed gravity platform. If returned this name SHOULD be used by
the client to decorate its branding.

````
  +------------+                             +---------------+
  |            |   X-Version: 1.0.0          |               |
  |            |   HEAD /                    |               |
  |            | <-------------------------- |               |
  |  Graviton  |   Link: rel="meta"          |  Graviphoton  |
  |            |   X-Version: 1.0.0          |               |
  |            |   [X-Name: Gravity]         |               |
  |            | --------------------------> |               |
  +------------+                             +---------------+
````

A full Link header pointing to /core/app SHOULD look like the following
example.

````
  Link: <http://example.com/core/app>; rel="apps"; type="application/json"
````

## frontend discovers apps

After discovering the backend the frontend MUST load a list of
apps from the endpoint given by the Link header with a rel value
"apps" and type "application/json".

The backend SHALL return a list of apps that are available to the
user.

````
  +------------+                             +---------------+
  |            |   GET /core/app             |               |
  |  Graviton  | <-------------------------- |  Graviphoton  |
  |            |   /core/app                 |               |
  |            | --------------------------> |               |
  +------------+                             +---------------+
````

The backend MAY use the returned apps so display links to other apps running
on the same gravity-plattform. It SHALL only link to apps returned by this
service.