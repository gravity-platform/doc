---
title: Naming Guide
layout: default
top_nav: guides
---

# Naming & Structure Guide

> There are only two hard things in Computer Science: cache invalidation and naming things.
> 
> -- Phil Karlton

Historically, naming things in IT was not considered that important. That was wrong. Naming *is* important.
If you still did not realize that, consider this: **You are dead wrong and probably did not think this through.**

In this context of services and APIs, naming becomes even more relevant. Logical, structured,
meaningful and (at best) instantly understandable naming is king. This guide outlines some basic principals
we want to adhere in regard to the naming our services.

**Important:** This guide covers naming of *services* built with/on the gravity platform, it is not relating to the code of 
the platform itself.  

## Basic philosophy

Naming is hard, we all know that. We do not say that those rules outlined here make the best naming of the world or anything. 
But an important aspect of naming is consistency. So the main matter is that all involved parties agree on some rules
regarding the naming to prevent horrible discrepancies in names.

Here are some basic important points:

* **All names together form something**<br/>Whether this is conscious or not, if you are using an application (like ours), over time a lingo (a small language) forms. All names public to the user should make sense when viewed as a whole. I'm trying to emphasize the importance of a naming strategy. If all names of an application visible to the user make sense as a whole, user experience is impacted positively.
* **Names should say something**<br/>A good name tells you something about the purpose and/or type of the thing. A name like `creationDate` is perfect - it's short, it classifies its context as a date and a creation context. What more do you want? Alway pick names that tell the user something.
* **Do not reinvent names**<br/>If you need to name something, *really check* beforehand if the same thing has been named already somewhere. Even if you would have a better name, it's often better to take the same "bad" name again to not add more confusion. In some cases, yes, let's introduce a new name for an already name thing, but please discuss this.
* **Keep it simple**<br/>Obviously, we urge you to make up names that mean something. But **please** do not overboard. NobodyLikesANameThatIsJustObviouslyTooLong. If names get too long, you are trying to say to much with it ;-)
* **You are writing in stone - think and discuss!**<br/>I mean it. You are naming something that (maybe) will be used many years to come in different shapes and/or scenarios. Time is well worth spent discussing names. We have all suffered from bad names in our careers, let's spare others the pain.. ;-)



## Guidelines

### Case

We **always** use [camelCase](http://en.wikipedia.org/wiki/CamelCase), starting with a lowercase letter.
You MUST NOT use the characters "_", "-" as part of a name.

Examples of good names:

```
thisIsGood
goodField
whyAreYouHere
```

And examples of *bad* names:

```
are_we_in_the_eighties
i
Meaning_The_Different_Ones
xyz
kleinerkampfschlumpf
cloud9
```

### Helpful precisions

Some fields should receive a suffix that helps the user instantly recognize some features.

Examples:

```
creationDate
customerId
```

We do not add those suffixes because the user could not find out the data type by himself. 
It's just to add more precise scope to the value. Let's say we would name it just `creation` or `customer`,
then it's not as specific. In the case of `creation` it's just unclear, in case of `customer`, one might
expect an embedded customer, not an id. 


### Banned words

Some words should not be used at all for various reasons, here they are in alphabetical order:

```
avaloq
finnova
ibis
kd_[l]nr
kube
mandate
microsoft
oracle
userbk_nr
``` 

Generally, do not use:

* Any brand/company names from software involved and/or source and/or target systems (anything that is not 
included in the list above) as those are subject to change.
* Names that are specific/exclusive to an external application. For example, do not use "DLL" as this 
would be Windows-specific, use "libraryFile" instead.
* Names that sounds english but are not or have a different meaning (I'm looking at you, *mandate*)  
* Names of people
* Any obscenities
* reserved words from the following environments/languages:
  * PHP
  * Javascript
  * MariaDB
  * MongoDB

## Common data structures

### Translatable

As you can see in [I18N](/doc/i18n.html), text in any form or shape that can be translatable should have
a common form.

When naming your translatible field, in addition to the normal rules, make sure to **not redundantely** name your field.

A good example would be: 

````javascript
{
  "status": {
    "en": "Open",
    "de": "Offen"
  }
}
````

A **bad example** would be:

````javascript
{
  "statusText": { // not good..
    "en": "Open",
    "de": "Offen"
  }
}
````

Just call it `status` in this example, it's obviously a text. We do not need you to tell us.. keep it simple ;-)

### "A code" (Translatable with additional id)

What we call "code" here is basically an inline Translatable but in addition, we also provide an id to the client.

This should look like this:

````javascript
{
  "recordType": {
    id: "LP"
    group: "objects",
    number: 3,
    text: {
      "en": "Long Play",
      "de": "Langspielplatte"
    }
  }
}
````
