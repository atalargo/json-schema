Opis Json Schema
====================
[![Build Status](https://travis-ci.org/opis/json-schema.png)](https://travis-ci.org/opis/json-schema)
[![Latest Stable Version](https://poser.pugx.org/opis/json-schema/v/stable.png)](https://packagist.org/packages/opis/json-schema)
[![Latest Unstable Version](https://poser.pugx.org/opis/json-schema/v/unstable.png)](https://packagist.org/packages/opis/json-schema)
[![License](https://poser.pugx.org/opis/json-schema/license.png)](https://packagist.org/packages/opis/json-schema)

Json Schema
-----------

**Opis Json Schema** is a PHP implementation for [json-schema](http://json-schema.org/) draft-07 and draft-06.

**The library's key features:**

- Fast validation (you can set maximum number of errors for a validation)
- Supports [json pointer](https://tools.ietf.org/html/rfc6901)
- Support [relative json pointer](https://tools.ietf.org/html/draft-luff-relative-json-pointer-00)
- Support for [uri-template](https://tools.ietf.org/html/rfc6570)
- Support for if-then-else (draft-07)
- Most of the string formats are supported
- Support for custom formats
- Support for custom media types
- Support for default value
- Support for custom filters (see `$filters`)
- Support for custom variables (local and global, see `$vars`)
- Schema reuse (see `$map`)

## License

**Opis Json Schema** is licensed under the [Apache License, Version 2.0](http://www.apache.org/licenses/LICENSE-2.0). 

## Requirements

* PHP 7 or higher

## Installation

This library is available on [Packagist](https://packagist.org/packages/opis/json-schema) and can be installed using [Composer](http://getcomposer.org).

```json
{
    "require": {
        "opis/json-schema": "^1.0.7"
    }
}
```

### Documentation

Current implementation extends standards by adding `$vars`, `$filters` and `$map` keywords.

#### $vars keyword

`$vars` keyword is used in conjunction with `$ref` (if `$ref` is an uri-template).

Properties:
- must be an object
- can reference any data
- can reference data by using `$ref` property (json pointer)
- can map referenced arrays using `$each` (see `$map` for example)

To disable `$vars` use `Opis\JsonSchema\Validator::varsSupport(false)`.

Example

```json
{
    "type": "object",
    "properties": {
        "prop1": {"type": "string"},
        "prop2": {
            "$ref": "http://example.com/{file}.json{#fragment}",
            "$vars": {
                "fragment": "static-fragment",
                "file": {"$ref": "1/prop1"} 
            }
        }
    },
    "required": ["prop1"]
}
```

For the following data
```json
{
    "prop1": "some-file",
    "prop2": null
}
```
the `$ref` will be `http://example.com/absolute/path/some-file.json#static-fragment`


#### $filters keyword

`$filters` keyword is used to add arbitrary filters in schema. 

Properties:

- `$filters` can be an object or an array of objects
- filter name is given by `$func` property
- can have arguments (using `$vars` property)
- filters will be checked only if the data matches the schema

To disable `$filters` use `Opis\JsonSchema\Validator::filtersSupport(false)`.

Example

```json
{
    "simple": {
        "$filters": {
            "$func": "filter_name"
        }
    },
    "with_vars": {
        "$filters": {
            "$func": "filter_name",
            "$vars": {
                "arg1": 5,
                "arg2": "some arg",
                "arg3": {
                    "$ref": "2/relative/path"
                }
            }
        }
    },
    "multiple": {
        "$filters": [
            {
                "$func": "filter_name_1"
            },
            {
                "$func": "filter_name_2",
                "$vars": {
                    "arg1": 5,
                    "arg2": "some arg",
                    "arg3": {
                        "$ref": "/absolute/path/to/data"
                    }
                }
            }
        ]
    }
}
```


#### $map keyword

`$map` keyword is used in conjunction with `$ref` (if `$ref` is an uri-template) to map existing object to a new structure.

Properties:
- behaves like `$vars` (but can also be an array, not only an object)
- schema referenced by `$ref` will receive the mapped object for validation 
(meaning that the schema should validate an object or an array)
- designed for schema reuse

To disable `$map` use `Opis\JsonSchema\Validator::mapSupport(false)`.

Example

Let's assume that a 3rd party gives us some standard validation rules for users.

```json
{
    "$id": "http://example.com/standards.json#",

    "definitions": {
        "standard-user": {
            "type": "object",
            "properties": {
                "name": {
                    "type": "string"
                },
                "birthday": {
                    "type": "string",
                    "format": "date"
                },
                "permissions": {
                    "type": "array",
                    "items": {
                        "$ref": "#/definitions/standard-user-permission"
                    }
                }
            },
            "required": ["name", "permissions"],
            "additionalProperties": false
        },
        "standard-user-permission": {
            "type": "object",
            "properties": {
                "name": {
                    "type": "string",
                    "enum": ["create", "read", "update", "delete"]
                },
                "enabled": {
                    "type": "boolean"
                }
            },
            "required": ["name", "enabled"],
            "additionalProperties": false
        }
    }
}
```

We can create or own user structure and still validate it using the provided schemas.

```json
{
    "type": "object",
    "properties": {
        "first-name": {
            "type": "string"
        },
        "last-name": {
            "type": "string"
        },
        "blog-permissions": {
            "type": "array",
            "items": {
                "type": "string"
            }
        }
    },
    "required": ["first-name", "last-name", "blog-permissions"],
    "additionalProperties": false,

    "$comment": "Below we will validate using the standards",
    "allOf": [
        {
            "$ref": "http://example.com/standards.json#/definitions/standard-user",
            "$map": {
                "name": {
                    "$ref": "0/first-name",
                    "$comment": "We map name value using first-name from our current object"
                },

                "$comment": "Our user doesn't have a birthday, so we just provide a valid one",
                "birthday": "1970-01-01",

                "permissions": {
                    "$ref": "0/blog-permissions",

                    "$comment": "Since our permissions have only string values we must convert them to an object"
                    "$each": {
                        "name": {
                            "$ref": "0",
                            "$comment": "Use the current string as name (it is a valid json pointer)"
                        },

                        "$comment": "Again, we provide a valid value for enabled"
                        "enabled": true
                    }
                }
            }
        }
    ]
}
```

So, for the following data

```json
{
    "first-name": "Json-Schema",
    "last-name": "Opis",
    "permissions": [
        "create",
        "read"
    ]
}
```

the mapped result (data that will be validated) is

```json
{
    "name": "Json-Schema",
    "birthday": "1970-01-01",
    "permissions": [
        {
            "name": "create",
            "enabled": true
        },
        {
            "name": "read",
            "enabled": true
        }
    ]
}
```

### PHP Examples

#### Basic example

```php
<?php

use Opis\JsonSchema\{
    Validator,
    ValidationResult,
    ValidationError
};

$validator = new Validator();


$schema = (object)[
    'minLength' => 3
];
/** @var ValidationResult $result */
$result = $validator->dataValidation("abc", $schema);

if ($result->isValid()) {
    echo "Valid", PHP_EOL;
}
else {
    /** @var ValidationError $error */
    $error = $result->getFirstError();
    echo "Invalid, error: ", $error->keyword(), PHP_EOL;
}

```

#### Loader

```php
<?php

use Opis\JsonSchema\{
    Validator,
    ValidationResult,
    ValidationError,
    Loaders\Memory as MemoryLoader
};

$loader = new MemoryLoader();

$loader->add((object) [
    "type" => "integer",
    "minimum" => 0
], "urn:positive-integer");

$loader->add((object) [
    "type" => "string",
    "format" => "email"
], "urn:mail");

$loader->add((object) [
    "type" => "object",
    "properties" => (object) [
        "age" => (object)['$ref' => "urn:positive-integer"],
        "mail" => (object)['$ref' => "urn:mail"],
    ],
    "required" => ["age", "mail"]
], "urn:simple-person");

$validator = new Validator();
$validator->setLoader($loader);

/** @var ValidationResult $result */
$result = $validator->uriValidation("someone@example.com", "urn:mail");
if ($result->isValid()) {
    echo "Valid e-mail", PHP_EOL;
}
else {
    /** @var ValidationError $error */
    $err = $result->getFirstError();
    echo "Invalid e-mail, error: ", $err->keyword(), PHP_EOL;
}

/** @var ValidationResult $result */
$result = $validator->uriValidation((object) [
    "age" => 23,
    "mail" => "someone@example.com",
], "urn:simple-person");

if ($result->isValid()) {
    echo "Valid simple-person", PHP_EOL;
}
else {
    /** @var ValidationError $error */
    $err = $result->getFirstError();
    echo "Invalid simple-person, error: ", $err->keyword(), PHP_EOL;
}
```

#### Vars

```php
<?php

use Opis\JsonSchema\{
    Validator,
    ValidationResult,
    ValidationError
};

$schema = (object) [
    "type" => "object",
    "properties" => (object) [
        "region" => (object)[
            "enum" => ["eu", "us"],
        ],
        "age" => (object)[
            // #/definitions/age-[eu|us]
            '$ref' => "#/{+globalVar}/{+localVar}-{+dataRefVar}",
            '$vars' => (object)[
                // local constant
                "localVar" => "age",
                // relative json-pointer applied to current data,
                "dataRefVar" => (object)[
                    '$ref' => "1/region"
                ],
            ]
        ]
    ],
    "required" => ["region"],
    "definitions" => (object)[
        "age-eu" => (object)[
            "type" => "integer",
            "minimum" => 18,
        ],
        "age-us" => (object)[
            "type" => "integer",
            "minimum" => 21,
        ],
    ]
];

$validator = new Validator();

// Set global variables 
$validator->setGlobalVars([
    'globalVar' => 'definitions'
]);

/** @var ValidationResult $result */
$result = $validator->dataValidation((object) [
    "age" => 20,
    "region" => "eu",
], $schema);

if ($result->isValid()) {
    echo "Valid", PHP_EOL;
}
else {
    /** @var ValidationError $error */
    $err = $result->getFirstError();
    echo "Invalid, error: ", $err->keyword(), PHP_EOL;
}
```

#### Filters

```php
<?php

use Opis\JsonSchema\{
    Validator,
    ValidationResult,
    ValidationError,
    FilterContainer,
    IFilter
};

$filters = new FilterContainer();

$filters->add("number", "modulo", new class implements IFilter {
    /**
     * @inheritDoc
     */
    public function validate($data, array $args): bool {
        $d = $args['divisor'] ?? 1;
        $r = $args['reminder'] ?? 0;
        return $data % $d == $r;
    }
});

$validator = new Validator();
$validator->setFilters($filters);

$schema = (object) [
    "type" => "integer",
    '$filters' => (object) [
        '$func' => 'modulo',
        '$vars' => (object) [
            'divisor' => 4,
            'reminder' => 3
        ],
    ]
];

/** @var ValidationResult $result */
$result = $validator->dataValidation(7, $schema);
if ($result->isValid()) {
    echo "Valid", PHP_EOL;
}
else {
    /** @var ValidationError $error */
    $err = $result->getFirstError();
    echo "Invalid, error: ", $err->keyword(), PHP_EOL;
}
```

#### Exceptions

```php
<?php

use Opis\JsonSchema\{
    Validator,
    ValidationResult
};
use Opis\JsonSchema\Exception\{
    AbstractSchemaException,
    DuplicateSchemaException,
    FilterNotFoundException,
    InvalidJsonPointerException,
    InvalidSchemaDraftException,
    InvalidSchemaException,
    SchemaDraftNotSupportedException,
    SchemaNotFoundException,
    SchemaKeywordException,
    UnknownMediaTypeException
};

$validator = new Validator();

try {
    /** @var ValidationResult $result */
    $result = $validator->uriValidation("str", "http://example.com/unexistent/schema.json");
}
catch (DuplicateSchemaException $e) {
    // Schema contains duplicates for $id (after resolving to base).
}
catch (FilterNotFoundException $e) {
    // Filter was not found. 
}
catch (InvalidJsonPointerException $e) {
    // Pointer is not valid.
}
catch (InvalidSchemaDraftException $e) {
    // $schema property is invalid.
}
catch (InvalidSchemaException $e) {
    // Schema is not a boolean or an object.
}
catch (SchemaDraftNotSupportedException $e) {
    // Draft version is not supported.
}
catch (SchemaNotFoundException $e) {
    // Schema could not be resolved by loader.
}
catch (SchemaKeywordException $e) {
    // A keyword from schema is invalid.
}
catch (UnknownMediaTypeException $e) {
    // MEdia type is not registered.
}
catch (AbstractSchemaException $e) {
    // Any schema exception.
}
```