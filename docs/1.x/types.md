---
layout: project
version: 1.x
title: Types
description: php opis json schema data types
keywords: opis, php, json, schema, data, type
---

# Types

The `type` keyword specifies the data type that a schema will use.
This keyword is not mandatory, and the value of keyword can be a string
representing a valid json type, or an array of strings representing
valid json types. The list of valid json types is:
- `string` - represents a string/text (`"a string"`, `"other string"`)
- `number` - represents an integer or a float (`-5`, `10`, `-5.8`, `10.2`)
- `integer` - represents an integer (`-100`, `125`, `0`)
- `boolean` - represents a boolean value (`true` or `false`)
- `null` - indicates that a value is missing (`null`)
- `object` - a key-value map, where the key must be a `string` and the
value can be any type (`{"key": "value", "other-key": 5}`)
- `array` - an ordered list of any data types (`[1, -2.5, "some string", null]`)

### String

`string` type validates a string/text.

```json
{
  "type": "string"
}
```

`"some text"` - valid
{:.alert.alert-success}

`""` - valid (empty string)
{:.alert.alert-success}

`12` - invalid (is integer/number)
{:.alert.alert-danger}

`null` - invalid (is null)
{:.alert.alert-danger}

### Number

`number` type validates any integer or float number.

```json
{
  "type": "number"
}
```

`5` - valid (integer)
{:.alert.alert-success}

`-10.8` - valid (float)
{:.alert.alert-success}

`"123"` - invalid (is string)
{:.alert.alert-danger}

`null` - invalid (is null)
{:.alert.alert-danger}

### Integer

`integer` type validates any integer number.

Please note that `integer` is a subtype of `number`.
{:.alert.alert-info}

```json
{
  "type": "integer"
}
```

`5` - valid (integer)
{:.alert.alert-success}

`-10` - valid (integer)
{:.alert.alert-success}

`5.0` - valid (integer)
{:.alert.alert-success}

`10.5` - invalid (is float)
{:.alert.alert-danger}

`"123"` - invalid (is string)
{:.alert.alert-danger}

`null` - invalid (is null)
{:.alert.alert-danger}

### Boolean

`boolean` type validates only `true` or `false` values.

```json
{
  "type": "boolean"
}
```

`true` - valid
{:.alert.alert-success}

`false` - valid
{:.alert.alert-success}

`"true"` - invalid (is string)
{:.alert.alert-danger}

`null` - invalid (is null)
{:.alert.alert-danger}

`0` - invalid (is integer/number)
{:.alert.alert-danger}

### Null

`null` type validates only the `null` value.

```json
{
  "type": "null"
}
```

`null` - valid
{:.alert.alert-success}

`""` - invalid (is string)
{:.alert.alert-danger}

`false` - invalid (is boolean)
{:.alert.alert-danger}

`0` - invalid (is integer/number)
{:.alert.alert-danger}

### Object

`object` type validates key-value maps.

```json
{
  "type": "object"
}
```

`{}` - valid (object with nu properties)
{:.alert.alert-success}

`{"prop1": "val1", "prop2": 2.5}` - valid
{:.alert.alert-success}

`12` - invalid (is integer/number)
{:.alert.alert-danger}

`null` - invalid (is null)
{:.alert.alert-danger}

`"some text"` - invalid (is string)
{:.alert.alert-danger}

### Array

`array` type validates ordered lists.

```json
{
  "type": "array"
}
```

`[]` - valid (empty array)
{:.alert.alert-success}

`[2, 1, "str", false, null, {}]` - valid
{:.alert.alert-success}

`12` - invalid (is integer/number)
{:.alert.alert-danger}

`null` - invalid (is null)
{:.alert.alert-danger}

`"1, 2, 3"` - invalid (is string)
{:.alert.alert-danger}

`{"0": 1, "1": 2, "2": 3}` - invalid (is object)
{:.alert.alert-danger}

### Combining types

You can use multiple types at once to restrict accepted data types,
or you can omit the `type` keyword to accept any type. The order of
types in the array doesn't matter, but you should not put the same
type more than once in array.

```json
{
  "type": ["object", "null"]
}
```

`{"a": 1}` - valid (is object)
{:.alert.alert-success}

`null` - valid (is null)
{:.alert.alert-success}

`"1, 2, 3"` - invalid (is string)
{:.alert.alert-danger}

`[{"a": 1}, {"b": 2}]` - invalid (is array)
{:.alert.alert-danger}

```json
{
  "type": ["number", "string", "null"]
}
```

`-10.5` - valid (is number)
{:.alert.alert-success}

`"some string"` - valid (is string)
{:.alert.alert-success}

`null` - valid (is null)
{:.alert.alert-success}

`false` - invalid (is boolean)
{:.alert.alert-danger}

`{"a": 1}` - invalid (is object)
{:.alert.alert-danger}

`[1, 2, 3]` - invalid (is array)
{:.alert.alert-danger}
