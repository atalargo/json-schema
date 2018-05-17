---
layout: project
version: 1.x
title: Opis Json Schema Validation
description: the opis json schema validation api
keywords: opis, json, schema, validation, api
---

# Validation



Let's see that in action.

```php
<?php

use Opis\JsonSchema\{
    Validator, ValidationResult, ValidationError
};

// Our schema
$schema = <<<'JSON'
{
    "type": "string",
    "minLength": 3
}
JSON;

// Must be provided decoded, so we use json_decode
$schema = json_decode($schema);

// We first create a new validator, because
// we can reuse this instance later
$validator = new Validator();

// Our data that will be validated
$data = "abc";

/** @var ValidationResult $result */
$result = $validator->dataValidation($data, $schema);

if ($result->isValid()) {
    echo $data, " is valid", PHP_EOL;
} else {
    /** @var ValidationError $error */
    $error = $result->getFirstError();
    echo $data, " is invalid", PHP_EOL;
    echo "Error: ", $error->keyword(), PHP_EOL;
    echo json_encode($error->keywordArgs(), JSON_PRETTY_PRINT), PHP_EOL;
}
```

The output of the above snippet is

```text
abc is valid
```

If we change the value of `$data` to `3`, the output becomes

```text
3 is invalid
Error: type
{
    "expected": "string",
    "used": "integer"
}
```

If we change the value of `$data` to `"ab"`, the output becomes

```text
ab is invalid
Error: minLength
{
    "min": 3,
    "length": 2
}
```
