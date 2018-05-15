---
layout: project
version: 1.x
title: Pointers
description: json pointers in opis json schema
keywords: opis, json, schema, pointer
---

# Json Pointers

To allow schema reuse and for a better separation of your validation logic,
you'll probably use multiple schema documents and reference them using URIs. 
But putting every block of validation in its own schema document is not always the best approach, especially when a block of validation
is used multiple times only in one schema.

Reasons to use json pointers

-  

Opis Json Schema supports both absolute and relative json schema pointers.

## Absolute pointers



You can find more details about json pointers [here](https://tools.ietf.org/html/rfc6901){:target="_blank"}.

## Relative pointers

You can find more details about relative json pointers [here](https://tools.ietf.org/html/draft-luff-relative-json-pointer-00){:target="_blank"}.
