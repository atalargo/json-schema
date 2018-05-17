---
layout: project
version: 1.x
title: Json Schema loader by id
description: the opis json schema loader/resolver system
keywords: opis, json, schema, validation, loader, resolver
---

# Loading and resolving json schemas

The loader object must resolve a schema by URI using an implementation
of `\Opis\JsonSchema\ISchemaLoader` interface.

This object is required by the validator only if you are using external references
(for example, the schemas are in two different files).

Currently, there is only one method that needs to be implemented by a loader.

#### loadSchema()

**Arguments**

- `string` $uri - The uri of the document schema

**Returns** `null|\Opis\JsonSchema\ISchema` - the resolved [schema document](php-schema.html) or `null` on failure.

## Existing loaders

Opis Json Schema ships by default with two existing loaders.

### Memory loader

You can use this loader for test.

```php
<?php
$loader = new \Opis\JsonSchema\Loaders\Memory();
$loader->add('{"type": "string"}', 'http://example.com/string.json');
$schema = $loader->loadSchema("http://example.com/string.json");
```

### File loader

You can use this loader to load schemas from filesystem.

```php
<?php
$loader = new \Opis\JsonSchema\Loaders\File(
    "http://example.com/", 
    "/path/to/schemas"
);
$schema = $loader->loadSchema("http://example.com/string.json");
// Will search the filesystem for /path/to/schemas/string.json
```
