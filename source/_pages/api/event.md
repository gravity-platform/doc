---
title: Events
layout: default
top_nav: api
---

# Events

Graviton supports an event based subscription feature. With this, it is possible for `worker agents` to subscribe
on certain events that happen. At the moment, this is limited to data changing events (`PUT`, `POST`, `DELETE`). 

This allows a `worker` to be notified on any data changes that happen - a worker can flexibly subscribe to whatever it is
interested.

If a `worker` is subscribed on a certain event, an `EventStatus` resource is created that allows a `worker` to update
its progress. This again makes it possible for interested parties (like GUI clients) to follow up on the status of certain
actions.

The interface between Graviton and the `worker` is a messaged based queue supporting the [AMQP protocol](https://en.wikipedia.org/wiki/Advanced_Message_Queuing_Protocol),
 we use [RabbitMQ](https://www.rabbitmq.com/). AMQP is widely adopted and bindings exists in virtually any environment,
 making it possible to write a `worker` in any language that possesses those bindings and a HTTP client.

When a worker is registered it can also create a `/event/action/` translation for frontend usage. Then when a event
is stored it can be linked to a description of the work being done. 

## When this should be used

This feature is intended for any features that require "background work", like sending an email when a certain record
has been created. One could write an agent that generates a PDF from the user input (subscribing on the relevant events),
then POSTing the PDF into the `/file` service.

The advantages on using this approach:

* It allows to implement additional (maybe client specific) logic without touching the core product
* As workers can be in any language, it gives more people the possibility to write this logic.
* The async'esque design with issuing a `EventStatus` object as a HTTP resource allows clients and workers to deal more easily with connection interruptions or crashes of different components.

## Detailed information

### Writing a worker

The typical workflow for a worker is as follows:

* The worker registers itself on the service `/event/worker/` by `PUT`ing his ID and subscription(s)
* The worker connects to a message bus (the same as Graviton) and creates or binds to a work queue.
* The worker waits for incoming messages indefinitely.
* Now, whenever a data change event happens to which the worker is subscribed, it will receive a message on the queue.
* If the worker receives a message, it must be JSON decoded first.
* Then, the worker needs to set it's status to `working` (by `PUT`ing a new version of the `EventStatus` object).
* The worker does his work
* The worker sets his status to `done` (again, by `PUT`ing a new version of the `EventStatus` object).


<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"><span class="picto-info-round" aria-hidden="true"></span> Update your status immediately!</h3>
    </div>
    <div class="panel-body" markdown="1">
    It is vital that the worker sets it's status to `working` *immediately* after receiving the message! This should happen
    as fast as possible. First, to let users know that the worker picked it up, but also to make it impossible for another instance
    of the worker to pick up the work.
    </div>
</div>

To enable this functionality, Graviton provides two services for Event handling:

* `/event/worker/`<br />Service where the worker registers itself
* `/event/status/`<br />A service providing `EventStatus` resources where the user sees the status (and the worker updates it).

### EventWorker: Registering your worker

Graviton needs to be aware which workers are interested in which events. An `EventStatus` will only be created on the users' 
data changing request if a worker is registered and subscribed to that specific event. Those registered worker IDs will then be included
in the `status` array property of the `EventStatus`, allowing the worker to set its status.

A worker must register itself with a simple `PUT` request to the `/event/worker/` collection. `curl` example:

```bash
curl -X PUT -H "Content-Type: application/json" -H "Cache-Control: no-cache" -d '
{
    "id": "example",
    "subscription": [
      {
        "event": "document.core.app.*"
      }
    ]
}' 'https://example.org/event/worker/example'
```

As you can see, the structure you PUT consists of the following:

* `id`<br />This is the ID of your worker as you register it. Also not that this must be in the url you `PUT` to, so you `PUT` to `/event/worker/<workerId>`.
* `subscription`<br />An array of objects. In each object you have an `event` property with the event name you want to subscribe to. You can subscribe to multiple events.

<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"><span class="picto-info-round" aria-hidden="true"></span> Pick your worker ID wisely</h3>
    </div>
    <div class="panel-body" markdown="1">
    Keep in mind that your worker ID *must* be unique within the Graviton instance your registering. So pick a worker ID that shall be unique, don't
    choose it to generic. Also note that a worker ID should be a simple word, not containing any spaces. Graviton will issue an error if you have
    invalid characters in your worker ID. See [the naming guide](/guides/naming_guide/) if you need inspiration.
    </div>
</div>

An example to subscribe to multiple events would be:

```bash
curl -X PUT -H "Content-Type: application/json" -H "Cache-Control: no-cache" -d '
{
    "id": "example",
    "subscription": [
      {
        "event": "document.core.app.*"
      },
      {
        "event": "document.i18n.language.*"
      }      
    ]
}' 'https://example.org/event/worker/example'
```

#### Finding the correct event names

For every event that happens, Graviton publishes its message using a defined *event name* on the queue.

This event name is always a 4 (four) component string and consists of:

* `[namespace].[bundle].[controller].[action]`

A typical event name then may be: `document.core.app.create`.

Detailed explanation of this components:

* `namespace`<br />A prefix for allowing different event kinds in the future. Currently, only the `document` namespace exists to catch changes on documents.
* `bundle`<br />This is the internal bundle name Graviton uses.
* `controller`<br />This is the internal controller name Graviton uses.
* `action`<br />This is a string identifying what action happened on that record. See below for possible values. 

Each service exposes its event names in its schema. If you check the schema of the `/core/app/` service by issuing a `GET` request
on `/schema/core/app/collection`, you will see a `x-event` array property. Example:

```
"x-events": [
    "document.core.app.update",
    "document.core.app.create",
    "document.core.app.delete"
]
```
* `*.update`<br />Fires when the user issues a `PUT` request.
* `*.create`<br />Fires when the user issues a `POST` request.
* `*.delete`<br />Fires when the user issues a `DELETE` request.

#### Event name levels

As you can see, the event name is always a string formatted as `[namespace].[bundle].[controller].[action]` (always four parts).
Note that this offers the possibility to select your scope by subscribing with multiple wildcards, as in this example:

* `document.core.app.create`<br />You will receive all record creating requests on `/core/app`
* `document.core.app.*`<br />You will receive all events on `/core/app`
* `document.core.*.*`<br />You will receive all events on `/core/*`
* `document.*.*.*`<br />You will receive all events on everything

All those strings are valid subscription names in `/event/worker/`

<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"><span class="picto-info-round" aria-hidden="true"></span> Narrow your scope</h3>
    </div>
    <div class="panel-body" markdown="1">
    We urge you to **not** abuse this. It should be common sense to only subscribe to the specific events you need. So please
    choose your subscription keys as specific as possible!
    </div>
</div>

### EventStatus: Track your status

If Graviton detects that a data changing request has been done on a resource a worker subscribed to, it will create an `EventStatus` 
 resource located in the `/event/status/` collection. It also incorporates the URL to this `EventStatus` resource into the 
 response sent to the client on his `PUT`/`POST`/`DELETE` request in the `Link` header. This allows the client to directly
 poll that URL for any changes workers do.
 
#### Anatomy of an EventStatus
 
A typical `EventStatus` looks like this:

```js
{
  "id": "ed5670e2a09835d2afe3e1b73e2939df",
  "createDate": "2015-09-10T14:27:47+0000",
  "eventName": "document.core.app.update",
  "status": [
    {
      "workerId": "worker1",
      "status": "opened"
    },
    {
      "workerId": "worker2",
      "status": "opened"
    },
    {
      "workerId": "shell-exec",
      "status": "done"
    }
  ],
  "information": []
}
```

As you can see, `status` is an array of objects. It is important to realize that *your worker shares the same status resource
with other workers*. You shall only modify the parts that concern your own worker. An `EventStatus` is shared between workers
to simplify the process of determining the *complete* status of a certain task the end user opened. For example, the creation of a certain
 resource may lead to the sending of an email and then to the generation of a PDF, actions done by different workers.
 
Note that this also opens to possibility for your worker to check for the status of a worker you maybe need to wait for.

So your main concern must be that your worker sets the `status` field of the array element that has its `workerId` property.

The possible `status` values are:

* `opened`<br />Graviton has created the event, no worker started its work.
* `working`<br />A worker is currently working on that item.
* `done`<br />A worker has finished his work.
* `failed`<br />A worker picked up the work, but finished in a failed state.

If you pass any other values for `status`, Graviton will reject your request as invalid.

### The `information` array property

The `/event/status/` resource contains an `information` property that gives workers the opportunity to write some specific information
regarding that can be picked up by the event creator (ie the client pulling the event status).

The `information` is an array property, containing objects of the following structure:

```js
{
    "information": [
        {
            "workerId": "worker2",
            "type": "error",
            "content": "Could not write file to disk."
        },    
        {
            "workerId": "worker1",
            "type": "info",
            "content": "See the generated document",
            "$ref": "https://example.org/file/sadsdsds892sdsd111"
        }
    ]
}
```

The properties of the information object are as follows:

* `workerId`<br />The ID of your worker. This field is `required`.
* `type`<br />The type of information. Valid values are: `debug, info, warning, error`. If you pass any other value, your request will be rejected. 
  This field is `required`.
* `content`<br />A string containing the information you want to pass. This field is `required`.
* `$ref`<br />An optional reference to any resource you may want to reference. This could be the place for a `/file` upload you generated with your worker.

### What you receive on the queue

Graviton sends data of the content-type `application/json` to the queue. Depending on your AMPQ client implementation this may
 already be deserialized to an object automatically or you will need to deserialize it yourself.
 
A typical message of Graviton would be like this:

```js
{"event":"document.core.app.update","document":{"$ref":"https:\/\/example.org\/core\/app\/admin"},"status":{"$ref":"https:\/\/example.org\/event\/status\/c013cf7ba58e396f9966d95f5b4238d4"}}
```

If you deserialize this, you will get a simple object:

```js
{
  "event": "document.core.app.update",
  "document": {
    "$ref": "https://example.org/core/app/admin"
  },
  "status": {
    "$ref": "https://example.org/event/status/c013cf7ba58e396f9966d95f5b4238d4"
  }  
}
```

We call this object structure `QueueEvent`. Let's have a look at its properties:

* `event`<br />This is the event name that Graviton published. See the section *Finding the correct event names* how Graviton composes those. 
* `document.$ref`<br />The public reachable URL to the document that was affected by the change.
* `status.$ref`<br />The URL to the `EventStatus` resource created for this event. Update your worker status in this object.

<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"><span class="picto-info-round" aria-hidden="true"></span> Routing keys</h3>
    </div>
    <div class="panel-body" markdown="1">
    Understand that the Graviton event name (*document.core.app.update* in this example) corresponds with the RabbitMQ *routing key*.
    So if you bind to the topic exchange with, *document.core.app.\**, you will receive all events concerning `/core/app/`.
    </div>
</div>

### Eventless operations

If no worker has subscribed to a certain event, Graviton will *not* create an `EventStatus` object and thus the `statusUrl`
property of the `QueueObject` is empty.

## The user perspective

Let's not forget the user perspective. If an API user makes a request on a resource to which (any number of) workers are subscribed,
the user will receive an `EventStatus` rel in his `Link` header response.

So if the user issues a PUT to `/core/app/` and there's a worker subscribed to that event in `/event/worker/`:

```bash
curl -X PUT -H "Content-Type: application/json" -H "Cache-Control: no-cache" '
{
    "id": "admin",
    "showInMenu": true,
    "order": 50,
    "name": {
      "en": "Administration"
    }
}' 'https://example.org/core/app/admin'
```

In the response we will see an addition to the `Link` header:

```
Link: <https://example.org/core/app/admin>; rel="self",<https://example.org/event/status/ece21bc6b9633458b213e6a5be2921e2>; rel="eventStatus"
```

Note that we now have an `eventStatus` rel entry. The client shall parse this and keep it for reference. This resource can be pulled in a desired
frequency to check for `status` property changes done by the workers. Using this, a client can simulate its user a progress display of the events it caused.

### When is something 'done'

The convention is that *all work has successfully been finished when all members of the `status` array have set their status to the string "done"*.

## Example

Let's try and sum this all up. If we create a worker, we will need to implement the logic for:

* Registering our worker
* Connecting to or creating the RabbitMq work queue and consuming messages
* Updating the `EventStatus` resource
* Doing our work
* Failure handling

We could describe every single step here, but at the end the code speaks for itself.

We created an [example worker implementation in our Github organization](https://github.com/libgraviton/graviton-worker-example/tree/master). Please
take a look at this example implementation (see the file [src/Worker.php](https://github.com/libgraviton/graviton-worker-example/blob/master/src/Worker.php) for a 
complete code example).

## Java base library

We maintain a [Java base library](https://github.com/libgraviton/graviton-worker-base-java) that simplifies the creation of Java based workers.
