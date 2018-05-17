---
layout: project
version: 1.x
title: Json Schema structure
description: php opis json schema document structure and metadata
keywords: opis, php, json, schema, validation, structure, metadata
---

# Json Schema structure

Json Schema it's a declarative way to write validations. It contains
only the steps that need to be performed in order to validate something.
The format used to write these steps is, of course, [JSON](https://www.json.org/){:target=_blank}.

**Opis Json Schema** is the software/library (for PHP) that performs those steps
and tells you the validation status.

## Data types

Because json schema is written in JSON format, it supports all JSON
types plus an addition - the integer type which is a subtype of number type.

- `string` - represents a string/text (`"a string"`, `"other string"`)
- `number` - represents an integer or a float (`-5`, `10`, `-5.8`, `10.2`)
- `integer` - represents an integer (`-100`, `125`, `0`)
- `boolean` - represents a boolean value (`true` or `false`)
- `null` - indicates that a value is missing (`null`)
- `object` - a key-value map, where the key must be a `string` and the
value can be any type (`{"key": "value", "other-key": 5}`)
- `array` - an ordered list of any data types (`[1, -2.5, "some string", null]`)

## Document structure

A valid json schema document must be a JSON object or boolean.
If it is a boolean, then the validation status is indicated by the value
of the boolean: `true` - valid, `false` - invalid.
If an object, it must contain the steps needed for validation. These steps
come in the form of **keywords**, and every keyword has a specific meaning.
Keywords are applied to data starting from the root of the document schema,
and descend to children.

Some keywords are purely decorative (metadata keywords which just describe the author intent),
some are for identifying a document or a subschema, and the rest are for
validity checks. Usually keywords work independently, there are only a few
exceptions;

Here are some examples

```json
true
```
Always valid.
{:.blockquote-footer}

```json
false
```
Always invalid.
{:.blockquote-footer}

```json
{}
```
Always valid because there are no steps defined.
{:.blockquote-footer}

```json
{
  "type": "string"
}
```
Validation status is keyword dependent (in this case) the data is valid
only if it holds a string.
{:.blockquote-footer}

`"test"` - valid
{:.alert.alert-success}

`123` - invalid
{:.alert.alert-danger}

## $schema keyword

This keyword is used to specify the desired schema version. 
The value of this keyword must be a string representing an URI.

Currently the supported URIs are:

- `http://json-schema.org/draft-07/schema#` - latest version 
- `http://json-schema.org/draft-06/schema#` - previous version

This keyword is not required, and if missing, the fallback is the latest
version URI.

There is only one difference between draft 06 and 07 is that draft 06 does not
support [if-then-else keywords](conditional-subschemas.html#if-then-else). 

## $id keyword

This keyword is used to specify an unique id for the document or document subschemas.
The value of this keyword must be a string representing an URI. All subschema
ids are resolved relative to document id.
It is not required, but as a best practice you should consider this
required.

The usage of this keyword will be covered in the next chapters.

## Metadata keywords

These keywords are not used for validation, but to describe the
validation and how it works. All keywords are optional.

### title

Contains a short description about the validation. The value of this
keyword must be a string.

```json
{
  "$schema": "http://json-schema.org/draft-07/schema#",
  "$id": "http://example.com/number.json#",
  "title": "Test if number",
  
  "type": "number"
}
``` 

### description

Contains a long description about the validation. The value of this
keyword must be a string.

```json
{
  "$schema": "http://json-schema.org/draft-07/schema#",
  "$id": "http://example.com/number.json#",
  "title": "Test if number",
  "description": "A data is considered number if it is an integer or a float.",
  
  "type": "number"
}
``` 

### examples

Contains a list of valid examples. The value of this keyword must be 
an array.

```json
{
  "$schema": "http://json-schema.org/draft-07/schema#",
  "$id": "http://example.com/number.json#",
  "title": "Test if number",
  "description": "A data is considered a number if is an integer or a float.",
  "examples": [-5.10, -2, 0, 5, 8.10],
  
  "type": "number"
}
``` 

## Some schema document examples

For most of the basic examples we will not use `$schema`, `$id` and metadata keywords,
but when writing schemas it is recommended to use at least the `$id` keyword.
{:.alert.alert-info}

Don't worry if you don't exactly understand every keyword, they are presented
in depth in the next chapters.

### Validating a simple user

```json
{
  "type": "object",
  "properties": {
    "name": {
      "type": "string"
    },
    "email": {
      "type": "string",
      "format": "email"
    },
    "age": {
      "type": "integer",
      "minimum": 18,
      "maximum": 150
    }
  },
  "required": ["email"]
}
```

`{"name": "John", "email": "john@example.com", "age": 25}` - valid
{:.alert.alert-success}

`{"email": "john@example.com"}` - valid (only the `email` is required)
{:.alert.alert-success}

`{"name": "John", "age": 25}` - invalid (required `email` is missing)
{:.alert.alert-danger}

`{"name": "John", "email": "john(at)example.com", "age": 25}` - invalid (not a valid email address)
{:.alert.alert-danger}

`{"email": 123}` - invalid (`email` must be a string)
{:.alert.alert-danger}

`{"email": "john@example.com", "age": 25.5}` - invalid (`age` must be an integer)
{:.alert.alert-danger}

`"john@example.com"` - invalid (must be an object)
{:.alert.alert-danger}


### Validating a list

```json
{
  "type": "array",
  "minItems": 2,
  "items": {
    "type": ["string", "number"]
  }
}
```

`[1, "a"]` - valid
{:.alert.alert-success}

`[-5.1, 10.8, 2]` - valid
{:.alert.alert-success}

`["a", "b", "c", "d", 4, 5]` - valid
{:.alert.alert-success}

`[1]` - invalid (must have at least 2 items)
{:.alert.alert-danger}

`["a", {"x": 1}]` - invalid (contains an object)
{:.alert.alert-danger}

`{"0": 1, "1": 2}` - invalid (not an array)
{:.alert.alert-danger}
