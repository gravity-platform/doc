---
title: Security
layout: default
top_nav: api
---

# Security and Authentication

The Api is designed to be used in a secure environment. Through a Gateway or a Web Application Firewall. 
Even so the API is able to use the forwarded header or cookie in order to allow access or deny and save changes done by 
the user.

The api it self has no login or logout functionality, only the identification and response about the user.

### Request sample

Get current user information

````
/person/whoami
````

Response may contain the following body.

```
{

    "usernmae": "john.doe",
    "firstName": "John",
    "lastName": "Doe"
}
```

### Configuration

Please refer to [SecurityBundle](https://github.com/libgraviton/graviton/tree/develop/src/Graviton/SecurityBundle) 
for detailed functionality and activation.