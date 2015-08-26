---
title: Service Definition
layout: default
top_nav: guides
---

# Service Definition Guide

This guide show you how to create your own services(end points) in graviton. When we speak about services or end points in this guide, we mean RESTful end points for API clients.
In the graviton environment it's very simple to create services. All what you must do, is create a ``JSON-File`` in your bundle under ``Resources/definition/``.

Each definition file must begin with an id and description field:

    {
        "id": "SimpleService",
        "description": "Simple service for explanation",

The file is subdivided in three parts.

## Part One - Service
In this part we define essential parameters for the end point. An overview about all:
    
    {
        "service": {
            "baseController": "\\Graviton\\CoreBundle\\Controller\\ShowcaseController",
            "routerBase": "/simple/service/",
            "readOnly": false,
            "recordOriginModifiable": true,
            "roles": ["GRAVITON_USER"],
            "fixtures": [...],
            "fixtureOrder": 45

- **baseController:**<br />Use this parameter to define an additional controller for overwrite the standard ``RestController`` and implement your own Actions(``GET``, ``POST`` ...).
**Attention:** The new controller must inherit from ``RestController``. This flag is *optional*.
- **routerBase:**<br /> Sets the path of URI(Uniform Resource Identifier). **Important:** The string must begin and end with a slash! It's compulsory *required*.
- **readOnly:**<br /> This flag determines whether the service is declared as a read only one. If *true* is set, the end point will only have ``GET``, ``HEAD`` and ``OPTIONS`` HTTP methods.
Otherwise it implements all others methods(``POST``, ``PUT``, ``PATCH`` and ``DELETE``) too. This flag is *required*.
- **recordOriginModifiable:**<br /> This is *optional* and has several meanings. If the flag isn't set, it will nothing happen. If it set, it will lead to an additional field to the service called ``recordOrigin``.<br />
In most cases this field is filled by an external process and want only showed to the client.
  - Value is *false*: Only records that don't contain the string ``core`` in the ``recordOrigin`` field are writable through the api. All edits to this records are not allowed and throw an error.
  All other records with other values are writable.<br />
  **Attention:** In this case the ``readOnly`` parameter must be *false*.
  - Value is *true*: All records are writable trough the API, also records with the value ``core``. This setting serves only for give out the ``recordOrigin`` to the client.<br />
- **roles:**<br /> Can be used for set which role can use this end point.
- **fixtures:**<br /> Array of objects with sample data. The appearance of the objects are defined in the ``target`` section. It can be also an empty array.
- **fixtureOrder:**<br /> It's an integer and defines the load order of the previous defined fixtures. The more lower the value, the more sooner the data will load.

## Part Two - Target
The second part describes the representation of the record which consumed by the API client. For use is only the ``fields`` attribute important.
All others are used by an external process to load records from remote sources.

    {
        "target": {
            "indexes": [],
            "relations": [],
            "fields": [
                {
                    "name": "id",
                    "title": "ID",
                    "type": "string",
                    "description": "unique identifier"
                },

All defined fields are JSON objects and have compulsively following attributes:

- **name**
- **title**
- **type**
- **description**

For an overview about all possible attributes use the [ShowCase definition file](https://github.com/libgraviton/GravitonTestServicesBundle/blob/develop/Resources/definition/ShowCase.json)

## Part Three - Source
This part is only for external processes to load records and can omit confidently.

## Summary

    {
        "id": "SimpleService",
        "description": "Simple service for explanation",
        "service": {
            "baseController": "\\Graviton\\CoreBundle\\Controller\\ShowcaseController",
            "routerBase": "/simple/service/",
            "readOnly": false,
            "recordOriginModifiable": true,
            "roles": ["GRAVITON_USER"],
            "fixtures": [...],
            "fixtureOrder": 45
        },
        "target": {
            "indexes": [],
            "relations": [],
            "fields": [
                {
                    "name": "id",
                    "type": "string",
                    "description": "unique identifier",
                    "title": "ID"
                }
            }
        }
    }
