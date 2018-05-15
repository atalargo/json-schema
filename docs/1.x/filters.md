---
layout: project
version: 1.x
title: Filters ($filters)
description: using custom filters in json schema to validate data
keywords: opis, json, schema, filter, $filters, validation
---

# Filters

Json Schema specification contains a lot of filters to validate data,
but most of them are only for _range check_ (like minimum, maximum, minLength, ...).
So, what happens if you want to check if something exists in a database?
Well, there cannot be such thing in json schema because it requires a lot 
of information (hostname, username, pass, query, ...) and it will be a pain
to debug or reuse the schema, not to mention about security concerns.

That's why we created a way to add PHP logic as filters in json schema, 
by adding a new non-standard keyword named `$filters`.

Custom filters can be expensive, so please note that `$filters` is the
last property checked.
{:.alert.alert-warning}

## General structure

In a json schema document, `$filters` can be: 
a string, an object or an array of strings and objects.

If your filter doesn't need any arguments (besides the value that is validated)
you can use it like a string.

```json
{
  "$filters": "myFilter"
}
```

If you need to send some arguments to filter use an object,
where `$func` keyword holds the filter name and `$vars` keyword (optional) holds
a map of arguments (see more info about [$vars](variables.html)).

```json
{
  "$filters": {
    "$func": "myFilter",
    "$vars": {
      "arg-name-1": 2,
      "arg-other": "something else" 
    }
  }
}
```

You can even use multiple filters by creating an array.

```json
{
  "$filters": [
    "firstFilter", 
    {
      "$func": "secondFilter",
      "$vars": {
        "var1": 1,
        "var2": "value"
      }
    },
    "lastFilter"
  ]
}
```

Please note that if you use an array of filters and one filter is not
valid the following filters will not be called.
{:.alert.alert-warning}

## Creating filters

A filter is class implementing `Opis\JsonSchema\IFilter` interface.
The `validate` method receives two arguments
- $value: the current value to validate
- $args: an associative array of variables

```php
<?php

use Opis\JsonSchema\IFilter;

class ModuloFilter implements IFilter
{
    public function validate($value, array $args): bool {
        $divisor = $args['divisor'] ?? 1;
        $reminder = $args['reminder'] ?? 0;
        return $value % $divisor == $reminder;
    }
}
```

## Using filters

Before using the `$filters` keyword in your schemas, make sure
to register them in a `Opis\JsonSchema\FilterContainer` object, and pass
that object to `Opis\JsonSchema\IValidator::setFilters()`.

When your register a filter you must specify:
- json data type (boolean, number, integer, string, null, array, object)
- name: the name you will use in your schemas
- the filter object that implements `Opis\JsonSchema\IFilter`

```php
<?php

use Opis\JsonSchema\{
    Validator,
    FilterContainer
};

// Create a new FilterContainer
$filters = new FilterContainer();

// Register our modulo filter
$filters->add("number", "modulo", ModuloFilter());

// Create a IValidator
$validator = new Validator();

// Set filters to be used by validator
$validator->setFilters($filters);

// Validation ...

```

Here is an example of schema that uses our modulo filter

```json
{
  "type": "number",
  "$filters": {
    "$func": "modulo",
    "$vars": {
      "divider": 4,
      "reminder": 3
    }
  }
}
```

This schema validates `7` _(7 % 4 == 3)_ but does not validate `17` _(17 % 4 == 1 != 3)_.