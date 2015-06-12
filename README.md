# Functional PHP: Functional primitives for PHP

[![Gitter](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/lstrojny/functional-php?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)
[![Build Status](https://secure.travis-ci.org/lstrojny/functional-php.svg)](http://travis-ci.org/lstrojny/functional-php) [![Dependency Status](https://www.versioneye.com/user/projects/523ed780632bac1b1100c359/badge.png)](https://www.versioneye.com/user/projects/523ed780632bac1b1100c359) [![Average time to resolve an issue](http://isitmaintained.com/badge/resolution/lstrojny/functional-php.svg)](http://isitmaintained.com/project/lstrojny/functional-php "Average time to resolve an issue") [![Percentage of issues still open](http://isitmaintained.com/badge/open/lstrojny/functional-php.svg)](http://isitmaintained.com/project/lstrojny/functional-php "Percentage of issues still open")

A set of functional primitives for PHP, heavily inspired by [Scala’s traversable
collection](http://www.scala-lang.org/archives/downloads/distrib/files/nightly/docs/library/scala/collection/Traversable.html),
[Dojo’s array functions](http://dojotoolkit.org/reference-guide/quickstart/arrays.html) and
[Underscore.js](http://documentcloud.github.com/underscore/)

  - Works with arrays and everything implementing interface `Traversable`
  - Consistent interface: for functions taking collections and callbacks, first parameter is always the collection, than the callback.
Callbacks are always passed `$value`, `$index`, `$collection`. Strict comparison is the default but can be changed
  - Calls 5.3 closures as well as usual callbacks
  - C implementation for performance but a compatible userland implementation is provided if you can’t install PHP
    extensions
  - All functions reside in namespace `Functional` to not raise conflicts with any other extension or library

[![Functional Comic](http://imgs.xkcd.com/comics/functional.png)](http://xkcd.com/1270/)

## Installation


### Install native extension
```bash
cd functional-php/
phphize
./configure
make
sudo make install
```


### Use userland extension

#### Using composer

Put the require statement for `functional-php` in your `composer.json` file and run `php composer.phar install`:

```json
{
    "require": {
        "lstrojny/functional-php": "*"
    }
}
```


#### Manually

Checkout functional-php and include the `_import.php`

```php
<?php
include 'path/to/functional-php/src/Functional/_import.php';
```

Everytime you want to work with Functional PHP and not reference the fully qualified name, add `use Functional as F;` on top of
your PHP file.


## Overview


### Functional\every() & Functional\invoke()

``Functional\every(array|Traversable $collection, callable $callback)``

``array Functional\invoke(array|Traversable $collection, string $methodName[, array $methodArguments])``
``mixed Functional\invoke_first(array|Traversable $collection, string $methodName[, array $methodArguments])``
``mixed Functional\invoke_last(array|Traversable $collection, string $methodName[, array $methodArguments])``

```php
<?php
use Functional as F;

// If all users are active, set them all inactive
if (F\every($users, function($user, $collectionKey, $collection) {return $user->isActive();})) {
    F\invoke($users, 'setActive', array(false));
}
```


### Functional\invoke_if()

``mixed Functional\invoke_if(mixed $object, string $methodName[, array $methodArguments, mixed $defaultValue])``

```php
<?php
use Functional as F;

// if $user is an object and has a public method getId() it is returned,
// otherwise default value 0 (4th argument) is used
$userId = F\invoke_if($user, 'getId', array(), 0);
```


### Functional\some()

``bool Functional\some(array|Traversable $collection, callable $callback)``

```php
<?php
use Functional as F;

if (F\some($users, function($user, $collectionKey, $collection) use($me) {return $user->isFriendOf($me);})) {
    // One of those users is a friend of me
}
```


### Functional\none()

``bool Functional\none(array|Traversable $collection, callable $callback)``

```php
<?php
use Functional as F;

if (F\none($users, function($user, $collectionKey, $collection) {return $user->isActive();})) {
    // Do something with a whole list of inactive users
}
```


### Functional\reject() & Functional\select()

``array Functional\select(array|Traversable $collection, callable $callback)``

``array Functional\reject(array|Traversable $collection, callable $callback)``

```php
<?php
use Functional as F;

$fn = function($user, $collectionKey, $collection) {
    return $user->isActive();
};
$activeUsers = F\select($users, $fn);
$inactiveUsers = F\reject($users, $fn);
```

Alias for `Functional\select()` is `Functional\filter()`

### Functional\drop_first() & Functional\drop_last()

``array Functional\drop_first(array|Traversable $collection, callable $callback)``

``array Functional\drop_last(array|Traversable $collection, callable $callback)``

```php
<?php
use Functional as F;

$fn = function($user, $index, $collection) {
    return $index <= 2;
};

// All users except the first three
F\drop_first($users, $fn);
// First three users
F\drop_last($users, $fn);
```

### Functional\pick()
Pick a single element from a collection of objects or an array by index.
If no such index exists, return the default value.

``array Functional\pick(array|Traversable $collection, mixed $propertyName, mixed $default, callable $callback)``

```php
<?php
use Functional as F;

$array = array('one' => 1, 'two' => 2, 'three' => 3);
F\pick($array, 'one'); //return 1;
F\pick($array, 'ten'); //return null;
F\pick($array, 'ten', 10); //return 10;
```

### Functional\pluck()
Fetch a single property from a collection of objects or arrays.

``array Functional\pluck(array|Traversable $collection, string|integer|float|null $propertyName)``

```php
<?php
use Functional as F;

$names = F\pluck($users, 'name');
```

### Functional\partition()
Splits a collection into two by callback. Truthy values come first

``array Functional\partition(array|Traversable $collection, callable $callback)``

```php
<?php
use Functional as F;

list($admins, $users) = F\partition($collection, function($user) {
    return $user->isAdmin();
});
```

###Functional\group()
Splits a collection into groups by the index returned by the callback

``array Functional\group(array|Traversable $collection, callable $callback)``

```php
<?php
use Functional as F;

$groupedUser = F\group($collection, function($user) {
    return $user->getGroup()->getName();
});
```

### Functional\reduce_left() & Functional\reduce_right()
Applies a callback to each element in the collection and reduces the collection to a single scalar value.
`Functional\reduce_left()` starts with the first element in the collection, while `Functional\reduce_right()` starts
with the last element.

``mixed Functional\reduce_left(array|Traversable $collection, callable $callback[, $initial = null])``

``mixed Functional\reduce_right(array|Traversable $collection, callable $callback[, $initial = null])``

```php
<?php
use Functional as F;

// $str will be "223"
$str = F\reduce_left(array(2, 3), function($value, $index, $collection, $reduction) {
    return $reduction . $value;
}, 2);

// $str will be "232"
$str = F\reduce_right(array(2, 3), function($value, $index, $collection, $reduction) {
    return $reduction . $value;
}, 2);
```

### Functional\flatten()
Takes a nested combination of collections and returns their contents as a single, flat array. Does not preserve indexes.

``array Functional\flatten(array|Traversable $collection)``

```php
<?php
use Functional as F;

$flattened = F\flatten(array(1, 2, 3, array(1, 2, 3, 4), 5));
// array(1, 2, 3, 1, 2, 3, 4, 5);
```

### Functional\first_index_of()
Returns the first index holding specified value in the collection. Returns false if value was not found

``array Functional\first_index_of(array|Traversable $collection, mixed $value)``

```php
<?php
use Functional as F;

// $index will be 0
$index = F\first_index_of(array('value', 'value'), 'value');
```

### Functional\last_index_of()
Returns the last index holding specified value in the collection. Returns false if value was not found

``array Functional\last_index_of(array|Traversable $collection, mixed $value)``

```php
<?php
use Functional as F;

// $index will be 1
$index = F\last_index_of(array('value', 'value'), 'value');
```

### Functional\true() / Functional\false()
Returns true or false if all elements in the collection are strictly true or false

``bool Functional\true(array|Traversable $collection)``
``bool Functional\false(array|Traversable $collection)``

```php
<?php
use Functional as F;

// Returns true
F\true(array(true, true));
// Returns false
F\true(array(true, 1));

// Returns true
F\false(array(false, false, false));
// Returns false
F\false(array(false, 0, null, false));
```

### Functional\truthy() / Functional\falsy()
Returns true or false if all elements in the collection evaluate to true or false

``bool Functional\truthy(array|Traversable $collection)``
``bool Functional\falsy(array|Traversable $collection)``

```php
<?php
use Functional as F;

// Returns true
F\truthy(array(true, true, 1, 'foo'));
// Returns false
F\truthy(array(true, 0, false));

// Returns true
F\falsy(array(false, false, 0, null));
// Returns false
F\falsy(array(false, 'str', null, false));
```

### Functional\contains()
Returns true if given collection contains given element. If third parameter is true, the comparison
will be strict

``bool Functional\contains(array|Traversable $collection, mixed $value[, bool $strict = true])``

```php
<?php
use Functional as F;

// Returns true
F\contains(array('el1', 'el2'), 'el1');

// Returns false
F\contains(array('0', '1', '2'), 2);
// Returns true
F\contains(array('0', '1', '2'), 2, false);
```

### Functional\zip()
Recombines arrays by index and applies a callback optionally

``array Functional\zip(array|Traversable $collection1[, array|Traversable ...[, callable $callback]])``

```php
<?php
use Functional as F;

// Returns array(array('one', 1), array('two', 2), array('three', 3))
F\zip(array('one', 'two', 'three'), array(1, 2, 3));

// Returns array('one|1', 'two|2', 'three|3')
F\zip(
    array('one', 'two', 'three'),
    array(1, 2, 3),
    function($one, $two) {
        return $one . '|' . $two;
    }
);
```

### Functional\with()
Invoke a callback on a value if the value is not null

```php
<?php
use Functional as F;

F\with($value, function($value) {
    $this->doSomethingWithValue($value);
});
```

### Functional\sort()
Sorts a collection with a user-defined function, optionally preserving array keys

```php
<?php
use Functional as F;

// Sorts a collection alphabetically
F\sort($collection, function($left, $right) {
    return strcmp($left, $right);
});

// Sorts a collection alphabetically, preserving keys
F\sort($collection, function($left, $right) {
    return strcmp($left, $right);
}, true);

// Sorts a collection of users by age
F\sort($collection, function($user1, $user2) {
    if ($user1->getAge() == $user2->getAge()) {
        return 0;
    }

    return ($user1->getAge() < $user2->getAge()) ? -1 : 1;
});
```

### Additional functions:

`void Functional\each(array|Traversable $collection, callable $callback)`  
Applies a callback to each element


`array Functional\map(array|Traversable $collection, callable $callback)`  
Applies a callback to each element in the collection and collects the return value


`array Functional\flat_map(array|Traversable $collection, callable $callback)`  
Applies a callback to each element in the collection and collects the return values flattening one level of nested arrays.


`mixed Functional\first(array|Traversable $collection[, callable $callback])`  
`mixed Functional\head(array|Traversable $collection[, callable $callback])`  
Returns the first element of the collection where the callback returned true. If no callback is given, the first element
is returned


`mixed Functional\last(array|Traversable $collection[, callable $callback])`  
Returns the last element of the collection where the callback returned true. If no callback is given, the last element
is returned


`mixed Functional\tail(array|Traversable $collection[, callable $callback])`  
Returns every element of the collection except the first one. Elements are optionally filtered by callback.


`integer|float Functional\product(array|Traversable $collection, $initial = 1)`  
Calculates the product of all numeric elements, starting with `$initial`


`integer|float Functional\ratio(array|Traversable $collection, $initial = 1)`  
Calculates the ratio of all numeric elements, starting with `$initial`


`integer|float Functional\sum(array|Traversable $collection, $initial = 0)`  
Calculates the sum of all numeric elements, starting with `$initial`


`integer|float Functional\difference(array|Traversable $collection, $initial = 0)`  
Calculates the difference of all elements, starting with `$initial`


`integer|float|null Functional\average(array|Traversable $collection)`  
Calculates the average of all numeric elements


`array Functional\unique(array|Traversable $collection[, callback $indexer[, bool $strict = true]])`  
Returns a unified array based on the index value returned by the callback, use `$strict` to change comparison mode


`mixed Functional\maximum(array|Traversable $collection)`  
Returns the highest element in the array or collection


`mixed Functional\minimum(array|Traversable $collection)`  
Returns the lowest element in the array or collection


`mixed Functional\memoize(callable $callback[, array $arguments = array()], [mixed $key = null]])`  
Returns and stores the result of the function call. Second call to the same function will return the same result without calling the function again


## Running the test suite
To run the test suite with the native implementation use `php -c functional.ini $(which phpunit) tests/`

To run the test suite with the userland implementation use `php -n $(which phpunit) tests/`

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
