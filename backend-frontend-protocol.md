# Backend - Frontend Protocol

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT",
"SHOULD", "SHOULD NOT", "RECOMMENDED",  "MAY", and "OPTIONAL" in 
this document are to be interpreted as described in RFC 2119.

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
installed gravity platform. This name SHOULD be used by the client
to decorate its branding.

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
  Link: <http://example.com/core/app>; type="application/vnd.graviton.app+json"; rel="meta"
````

## frontend discovers apps

After discovering the backend the frontend MUST load a list of
apps from the endpoint given by the Link header with a rel value
"meta" and type "application/vnd.graviton.app+json".

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
