---
layout: project
version: 1.x
title: About
---

# About

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

```
composer require opis/json-schema
```


