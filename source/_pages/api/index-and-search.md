---
title: Indexing
layout: default
top_nav: api
---

# Indexing and Search indexes

Standard indexes can be defined in definition json so that Symfony can generated the DB schema and speed up your
application. Be aware about limitations: 

 [MongoDb Indexes](https://docs.mongodb.com/manual/indexes/)


Since MongoDB 3 you can define Text Indexes and weighting, if done with care the search results can be fast a very accurate.
Better than using complex [RQL's](/api/rql). Min word length is 3 chars.

 [MongoDB Text Indexes](https://docs.mongodb.com/manual/text-search/)

This works like having one big sentence with the words of the selected fields and the more words matching the search
the higher score.


### Request search sample

Search user John. If more words are entered in the search brackets query will perform an "and" between them. 

````
/people/?search(john)
````

Response may contain the following body, all people named john. 

```
[{

    "usernmae": "john.doe",
    "firstName": "John",
    "lastName": "Doe"
}, .... ]
```

### Configuration

Standard indexes and TextIndexes are located inside the Target block of the definition file.
Text Indexes needs to be as object. 
The higher the weight is the more important, and sorting will be done by weighted sum of coincidences, score. 

````
     "indexes": [
      "username"
    ],
    "textIndexes": [
      {
        "field": "username",
        "weight": 20
      },
      {
        "field": "firstName",
        "weight": 20
      },
      {
        "field": "lastName",
        "weight": 50
      },
      {
        "field": "address.city",
        "weight": 20
      }
    ]
````