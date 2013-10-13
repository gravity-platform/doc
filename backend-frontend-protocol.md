# Backend - Frontend Protocol

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT",
"SHOULD", "SHOULD NOT", "RECOMMENDED",  "MAY", and "OPTIONAL" in 
this document are to be interpreted as described in RFC 2119.

## frontend discovers backend

The frontend MUST check for Link headers on the root of the server
it is hosted on. Non web based frontends SHOULD use a server that
is configurable in their settings. They SHALL NOT contain hard coded
references to a given backend server.

A HEAD request to / MUST return a Link header pointing to a list of
app resources. The client MUST include its own version in the
request.
It MUST return a X-Version header specifying the version of the
installed graviton server. It is REQUIRED that the frontend displays
this version along with its own version in a human readable format.
Both versions MUST adhere to the
[Semantic Versioning 2.0.0](http://semver.org) standard.

The backend MAY return an X-Name header containing a name of the
installed gravity platform. This name SHOULD be used by the client
to decorate its branding.

````
  +------------+                                  +---------------+
  |            |   X-Version: 1.0.0               |               |
  |            |   HEAD /                         |               |
  |            | <------------------------------- |               |
  |  Graviton  |   Link: </core/app>; rel="app"   |  Graviphoton  |
  |            |   X-Version: 1.0.0               |               |
  |            |   [X-Name: Gravity]              |               |
  |            | -------------------------------> |               |
  +------------+                                  +---------------+
````

## frontend discovers apps

The frontend MUST initiate loading a list of apps from the Link
endpoint given by the Link headers rel="app" location where the
backend MUST return a list of apps for the frontend to display.

````
  +------------+                                  +---------------+
  |            |   GET /core/app                  |               |
  |  Graviton  | <------------------------------- |  Graviphoton  |
  |            |   /core/app                      |               |
  |            | -------------------------------> |               |
  +------------+                                  +---------------+
````
