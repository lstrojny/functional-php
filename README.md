# Functional PHP: Functional primitives for PHP

[![Gitter](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/lstrojny/functional-php?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)
[![Build Status](https://secure.travis-ci.org/lstrojny/functional-php.svg)](http://travis-ci.org/lstrojny/functional-php) [![Dependency Status](https://www.versioneye.com/user/projects/523ed780632bac1b1100c359/badge.png)](https://www.versioneye.com/user/projects/523ed780632bac1b1100c359) [![Average time to resolve an issue](http://isitmaintained.com/badge/resolution/lstrojny/functional-php.svg)](http://isitmaintained.com/project/lstrojny/functional-php "Average time to resolve an issue") [![Percentage of issues still open](http://isitmaintained.com/badge/open/lstrojny/functional-php.svg)](http://isitmaintained.com/project/lstrojny/functional-php "Percentage of issues still open") [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/lstrojny/functional-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/lstrojny/functional-php/?branch=master)

*NOTE:* functional-php used to come with a C extension that implemented most of the functions natively. As the
performance differences  weren’t that huge compared to the maintenance cost it has been removed.

A set of functional primitives for PHP, heavily inspired by [Scala’s traversable
collection](http://www.scala-lang.org/archives/downloads/distrib/files/nightly/docs/library/scala/collection/Traversable.html),
[Dojo’s array functions](http://dojotoolkit.org/reference-guide/quickstart/arrays.html) and
[Underscore.js](http://underscorejs.org/)

  - Works with arrays and everything implementing interface `Traversable`
  - Consistent interface: for functions taking collections and callbacks, first parameter is always the collection, than the callback.
Callbacks are always passed `$value`, `$index`, `$collection`. Strict comparison is the default but can be changed
  - Calls 5.3 closures as well as usual callbacks
  - All functions reside in namespace `Functional` to not raise conflicts with any other extension or library

[![Functional Comic](http://imgs.xkcd.com/comics/functional.png)](http://xkcd.com/1270/)

## Installation

### Using composer

Put the require statement for `functional-php` in your `composer.json` file and run `php composer.phar install`:

```json
{
    "require": {
        "lstrojny/functional-php": "~1.2"
    }
}
```

### Manually

Checkout functional-php and include the `_import.php`

```php
<?php
include 'path/to/functional-php/src/Functional/_import.php';
```

## Docs
[Read the docs](docs/00-index.md)

## Running the test suite
To run the test suite use `vendor/bin/phpunit tests/`

## Mailing lists
 - General help and development list: http://groups.google.com/group/functional-php
 - Commit list: http://groups.google.com/group/functional-php-commits

## Thank you
 - [Richard Quadling](https://github.com/RQuadling) and [Pierre Joye](https://github.com/pierrejoye) for Windows build
   help
 - [David Soria Parra](https://github.com/dsp) for various ideas and the userland version of `Functional\flatten()`
 - [Max Beutel](https://github.com/maxbeutel) for `Functional\unique()`, `Functional\invoke_first()`,
   `Functional\invoke_last()` and all the discussions
 - The people behind [Travis CI](http://travis-ci.org/) for continuous integration
