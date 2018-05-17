---
layout: project
version: 1.x
title: Json Schema references ($ref)
description: using opis json schema $ref keyword to reuse schema by references 
keywords: opis, json, schema, validation, reference, $ref
---

# References (reusing schemas)

Remember when we mentioned about the `$id` keyword in the [Json Schema Structure](structure.html#id-keyword)?
Now is time to use that `$id` for something. As we said, a json schema document
can be identified by an unique id. 

Consider that we have two json schema documents:
one validates a custom email address and the other one validates an user which must
have that custom email address. In order to reuse the custom email validator
we make a reference to it by using the `$ref` keyword. Let's see how it will look.

```json
{
  "$id": "http://example.com/custom-email-validator.json#",
  
  "type": "string",
  "format": "email",
  "pattern": "@example\\.test$"
}
```
The custom email validator.
{:.blockquote-footer}

```json
{
  "type": "object",
  "properties": {
    "name": {
      "type": "string",
      "minLength": 2
    },
    "email": {
      "$ref": "http://example.com/custom-email-validator.json#"
    }
  },
  "required": ["name", "email"],
  "additionalProperties": false
}
```
The user validator.
{:.blockquote-footer}

`{"name": "Opis", "email": "opis@example.test"}` - valid
{:.alert.alert-success}

`{"name": "Opis", "email": "opis@example.com"}` - invalid (`pattern` not matched)
{:.alert.alert-danger}

And what happens here is something which produces a result similar to
the following schema

```json
{
  "type": "object",
  "properties": {
    "name": {
      "type": "string",
      "minLength": 2
    },
    "email": {
        "type": "string",
        "format": "email",
        "pattern": "@example\\.test$"
    }
  },
  "required": ["name", "email"],
  "additionalProperties": false
}
```

This is pretty cool, because now you can write and link different schemas.
You can use `$ref` wherever you need, as many times as you need.

This is the first step in schema reusing.

### $ref

An instance is valid against this keyword if is valid against the
schema that points to the location indicated in the value of this keyword.
The value of this keyword must be a string representing an URI, URI reference 
or an URI template. When present, other validation
keywords (except: [`$vars`](variables.html) and [`$map`](mappers.md)),
 placed on the same level will have no effect. 

This keyword can be applied to any instance type.
