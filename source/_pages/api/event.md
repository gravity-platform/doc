---
title: Events
layout: default
top_nav: api
---

# Events

Graviton supports an event based subscription feature. With this, it is possible for `worker agents` so subscribe
on certain events that happen. At the moment, this is limited to data changing events (`PUT`, `POST`, `DELETE`). 

This allows a `worker` to be notified on any data changes that happen - a worker can flexibly subscribe on whatever it is
interested.

If a `worker` is subscribed on a certain event, an `EventStatus` resource is created that allows a `worker` to update
its progress. This again makes it possible for interested parties (like GUI clients) to follow up on the status of certain
actions.

The interface between Graviton and the `worker` is a messaged based queue supporting the [AMQP protocol](https://en.wikipedia.org/wiki/Advanced_Message_Queuing_Protocol),
 we use [RabbitMQ](https://www.rabbitmq.com/). AMQP is widely adopted and bindings exists in virtually any environment,
 making it possible to write a `worker` in any language that possesses those bindings and a HTTP client.

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

* The worker registers itself on the service `/event/worker/`, `PUT`ing his ID and subscription(s)
* The worker connects to the message queue (the same as Graviton), binds to the topic exchange `graviton`.
* The worker waits for incoming messages indefinitely.
* Now, whenever a data change event happens to which the worker is subscribed, it will receive a message on the queue.
* If the worker receives a message, it must be JSON decoded first.
* Then, the worker needs to set it's status to `working` (by `PUT`ing a new version of the `EventStatus` object).
* The worker does his work
* The worker sets his status to `done` (again, by `PUT`ing a new version of the `EventStatus` object).

> ***Update your status immediately!***<br>
> It is vital that the worker sets it's status to `working` *immediately* after receiving the message! This should happen
> as fast as possible. First off, to let users know that the worker picked it up, but also to make it impossible for another worker
> to pick up the work.

### The EventStatus resource

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
  "errorInformation": []
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
* `done`<br />A worker has finished his work
* `failed`<br />A worker has done is worked, but in a failed state. You may find more information in the `errorInformation` property.

### EventWorker: Registering your subscription

#### Finding the correct event names

For every event that happens, Graviton generates an *event name*. This event name is always a 4 (four) component string and
consists of:

* `[namespace].[bundle].[controller].[action]`

A typical event name then may be: `document.core.app.create`.

Detailed explanation of this components:

* `namespace`<br />A prefix for allowing different event kinds in the future. Currently, only the `document` namespace exists to catch changes on documents.
* `bundle`<br />This is the internal bundle name Graviton uses. This is *mostly* the first part of the public URL, so in `/core/app/` this leads to `core`.
* `controller`<br />This is the internal controller name Graviton uses. This is *mostly* the second part of the public URL, so in `/core/app/` this leads to `app`.
* `action`<br />This is a string identifying what action happened on that record. Possible values are `update` (for `PUT` requests), `create` (for `POST` request) and `delete` (for `DELETE` requests) 

We recommend that you just open a connection to the queue, provoke a certain action (in `develop`) and observe the events, there you can see the event names.
See section (Queue verbosity)[#Queue verbosity] below.

### What you receive on the queue


### Queue verbosity

Note that Graviton writes *any* data change to the queue, regardless if a worker is subscribed to or not. This allows
to connect to the queue "blindly" and see what happens.

If no worker has subscribed to a certain event, Graviton will *not* create an `EventStatus` object and thus the `statusUrl`
property of the `QueueObject` is empty. But it allows you to see what is being fired when a certain event occurs or to debug
any problems in your worker. To receive all messages, you can bind to the `graviton` topic exchange routing key `#`.



## Example

### Failed status
