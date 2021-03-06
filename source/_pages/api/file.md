---
title: File
layout: default
top_nav: api
---

# File service

The file service in graviton is reachable at the ``/file/`` endpoint. It was developed as a catch-all solution to clients file storage needs.

Graviton file services are backed by either machine local storage or an S3 bucket depending on your deploy.

Clients may use the service to store assets like images or also to store content generated by customers.

Since the file service operates on files of all kinds of MIME-types and also stores metadata on one single endpoint it has a larger than usual surface.

For further information about file service please visit: [FileBundle](https://github.com/libgraviton/graviton/tree/develop/src/Graviton/FileBundle)

## Usage

### Upload a file

Create and upload a simple text file as follows.

````bash
echo "Hallo Graviton" > test.txt

curl -v -X POST -H "Content-Type: text/plain" \
    -T'{test.txt}' https://example.org/file
````

Observe the ``Location`` header in the output from the ``POST`` request. It tells you where the file was stored and has the following format:

```
Location: /file/55bb584a08420b5f288b457c
```

### Retrieve file and metadata

Get the files contents by issuing a GET request with the corresponding MIME-type.

````bash
curl -H "Accept: text/plain" \
    https://example.org/file/55bb584a08420b5f288b457c
````

Or get the accompanying metadata by requesting JSON data.

````bash
curl -H "Accept: application/json" \
    https://example.org/file/55bb584a08420b5f288b457c
````
### Update file metadata

When updating files it is important to remember that you should always base your edits on the original data. 

You need to specify a JSON MIME-type to update the files metadata.

````bash
curl -H "Accept: application/json" \
    https://example.org/file/55bb584a08420b5f288b457c > test.json
    
# Edit test.json in vim and add metadata.filename and some links

curl -v -X PUT -H "Content-Type: application/json" \
    -T'{test.json}' https://example.org/file/55bb584a08420b5f288b457c
````

If you try to edit any of the read only data in the resource graviton will complain with an error and reject the updated data.

The following fields are read only and get updated by graviton as needed.

* ``metadata.createData``
* ``metadata.modificationDate``
* ``metadata.size``
* ``metadata.mime``

### Update file content

To update a file you will need to send the corresponding MIME-type headers.

````bash
curl -v -X PUT -H "Content-Type: text/plain" \
    -T'{test.txt}' https://example.org/file/55bb584a08420b5f288b457c
````

### Upload File and Metadata in one go

By introducing the content-type *multipart/form-data* it is now possible to send the file and the metadata in one PUT or POST request.
Since this is basically a form submit the information is send as form fields:
- *metadata* » use for the metadata formerly sent as payload in step 2
- *upload* » use to send the file to be stored

```bash
curl -X POST \
     -F 'metadata={"links":[],"metadata": {"action":[{"command":"print"},{"command":"archive"}]}}' \
     -F upload=@test.txt \
     https://example.org/file
```

```bash
curl -X PUT \
     -F 'metadata={"id": "myPersonalFile","links":[],"metadata": {"action":[{"command":"print"},{"command":"archive"}]}}' \
     -F upload=@test.txt \
     https://example.org/file/myPersonalFile
```

Both PUT and POST request will not accept more than one file to be uploaded. In case multiple files are sent only the
first file will be recognized.
The set of readonly metadata fields is extend by the field: ```filename```, which will now be set by graviton.

## Example data (annotated)

```js
{
    "id": "55bb584a08420b5f288b457c",
    // array of links that may point to any other resource
    "links": [
        {
            // URL of linked resource
            "$ref": "https://example.org/person/customer/123",
            // link 'type', it is up to the clients to define the types they want to use
            "type": "owner"
        },
        {
            "$ref": "https://example.org/core/module/123", 
            "type": "module"
        }
    ], 
    "metadata": {
        // read only fields in metadata get managed by the server
        // createData is such a field and gets set to the time of the initial upload
        "createDate": "2015-08-06T10:51:20+0000",
        // name for display purposes, may be updated by user
        "filename": "fileName.txt",
        // read only and inferred from the uploaded file
        "mime": "text/plain",
        // read only and set when a file is replaced
        "modificationDate": "2015-08-06T10:51:20+0000",
        // read only and inferred from the upload
        "size": 12,
        // key/value store for additional properties. Also used for add information for printing and archiving
        "additionalProperties": [
            {"name": "propertyName", "value": "aValue"},
            {"name": "secondProp", "value": "otherValue"}
        ]
    }
}
```

Please reference the [schema](https://graviton.nova.scapp.io/schema/file/item) for more details on the data format of the file service.
