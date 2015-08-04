---
title: User Guide
layout: default
---
# User Guide

This is a user guide for the service api's from graviton.

### /file api



#### post a file to the  collection
1. create a simple textfile
````bash
echo "Hallo Graviton" > test.txt
````
2. curl request (POST) to create the file
````bash
curl -H "Content-Type: text/plain" -X POST -T'{test.txt}' http://localhost:8000/file
````
#### get the content of the file

````bash
curl http://127.0.0.1:8000/file/55bb584a08420b5f288b457c
````
###### returns 'Hallo Graviton'



