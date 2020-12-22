# Functional PHP: Functional primitives for PHP

[![Test](https://github.com/lstrojny/functional-php/actions/workflows/test.yaml/badge.svg)](https://github.com/lstrojny/functional-php/actions/workflows/test.yaml)

*NOTE:* functional-php used to come with a C extension that implemented most of the functions natively. As the
performance differences weren’t that huge compared to the maintenance cost it has been removed.

A set of functional primitives for PHP, heavily inspired by [Scala’s traversable
collection](http://www.scala-lang.org/archives/downloads/distrib/files/nightly/docs/library/scala/collection/Traversable.html),
[Dojo’s array functions](http://dojotoolkit.org/reference-guide/quickstart/arrays.html) and
[Underscore.js](http://underscorejs.org/)

- Works with arrays and everything implementing interface `Traversable`
- Consistent interface: for functions taking collections and callbacks, first parameter is always the collection, then the callback.
  Callbacks are always passed `$value`, `$index`, `$collection`. Strict comparison is the default but can be changed
- Calls 5.3 closures as well as usual callbacks
- All functions reside in namespace `Functional` to not raise conflicts with any other extension or library

[![Functional Comic](http://imgs.xkcd.com/comics/functional.png)](http://xkcd.com/1270/)

## Installation

Run the following command in your project root:

    composer require lstrojny/functional-php

## Docs

[Read the docs](docs/functional-php.md)

## Contributing

1. Fork and `git clone` the project
2. Install dependencies via `composer install`
3. Run the tests via `composer run tests`
4. Write code and create a PR

## Mailing lists

- General help and development list: http://groups.google.com/group/functional-php
- Commit list: http://groups.google.com/group/functional-php-commits

## Thank you

- [Richard Quadling](https://github.com/RQuadling) and [Pierre Joye](https://github.com/pierrejoye) for Windows build
   help
- [David Soria Parra](https://github.com/dsp) for various ideas and the userland version of `Functional\flatten()`
- [Max Beutel](https://github.com/maxbeutel) for `Functional\unique()`, `Functional\invoke_first()`,
   `Functional\invoke_last()` and all the discussions
- [An Phan](https://github.com/phanan) for [many](https://github.com/lstrojny/functional-php/pulls?q=author%3Aphanan)
  great contributions
