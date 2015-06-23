# Functional PHP: Functional primitives for PHP

[![Gitter](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/lstrojny/functional-php?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)
[![Build Status](https://secure.travis-ci.org/lstrojny/functional-php.svg)](http://travis-ci.org/lstrojny/functional-php) [![Dependency Status](https://www.versioneye.com/user/projects/523ed780632bac1b1100c359/badge.png)](https://www.versioneye.com/user/projects/523ed780632bac1b1100c359) [![Average time to resolve an issue](http://isitmaintained.com/badge/resolution/lstrojny/functional-php.svg)](http://isitmaintained.com/project/lstrojny/functional-php "Average time to resolve an issue") [![Percentage of issues still open](http://isitmaintained.com/badge/open/lstrojny/functional-php.svg)](http://isitmaintained.com/project/lstrojny/functional-php "Percentage of issues still open") [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/lstrojny/functional-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/lstrojny/functional-php/?branch=master)

A set of functional primitives for PHP, heavily inspired by [Scala’s traversable
collection](http://www.scala-lang.org/archives/downloads/distrib/files/nightly/docs/library/scala/collection/Traversable.html),
[Dojo’s array functions](http://dojotoolkit.org/reference-guide/quickstart/arrays.html) and
[Underscore.js](http://documentcloud.github.com/underscore/)

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

Everytime you want to work with Functional PHP and not reference the fully qualified name, add `use Functional as F;` on top of
your PHP file or use `use function Functional\function_name`.


## Overview


### Functional\every() & Functional\invoke()

``Functional\every(array|Traversable $collection, callable $callback)`` 

``array Functional\invoke(array|Traversable $collection, string $methodName[, array $methodArguments])`` 
``mixed Functional\invoke_first(array|Traversable $collection, string $methodName[, array $methodArguments])`` 
``mixed Functional\invoke_last(array|Traversable $collection, string $methodName[, array $methodArguments])`` 

```php
<?php
use function Functional\every;
use function Functional\invoke;

// If all users are active, set them all inactive
if (every($users, function($user, $collectionKey, $collection) {return $user->isActive();})) {
    invoke($users, 'setActive', [false]);
}
```


### Functional\invoke_if()

``mixed Functional\invoke_if(mixed $object, string $methodName[, array $methodArguments, mixed $defaultValue])`` 

```php
<?php
use function Functional\invoke_if;

// if $user is an object and has a public method getId() it is returned,
// otherwise default value 0 (4th argument) is used
$userId = invoke_if($user, 'getId', [], 0);
```


### Functional\some()

``bool Functional\some(array|Traversable $collection, callable $callback)`` 

```php
<?php
use function Functional\some;

if (some($users, function($user, $collectionKey, $collection) use($me) {return $user->isFriendOf($me);})) {
    // One of those users is a friend of me
}
```


### Functional\none()

``bool Functional\none(array|Traversable $collection, callable $callback)`` 

```php
<?php
use functional Functional\none;

if (none($users, function($user, $collectionKey, $collection) {return $user->isActive();})) {
    // Do something with a whole list of inactive users
}
```


### Functional\reject() & Functional\select()

``array Functional\select(array|Traversable $collection, callable $callback)`` 

``array Functional\reject(array|Traversable $collection, callable $callback)`` 

```php
<?php
use function Functional\select;
use function Functional\reject;

$fn = function($user, $collectionKey, $collection) {
    return $user->isActive();
};
$activeUsers = select($users, $fn);
$inactiveUsers = reject($users, $fn);
```

Alias for `Functional\select()` is `Functional\filter()`

### Functional\drop_first() & Functional\drop_last()

``array Functional\drop_first(array|Traversable $collection, callable $callback)`` 

``array Functional\drop_last(array|Traversable $collection, callable $callback)`` 

```php
<?php
use function Functional\drop_first;
use function Functional\drop_last;

$fn = function($user, $index, $collection) {
    return $index <= 2;
};

// All users except the first three
drop_first($users, $fn);
// First three users
drop_last($users, $fn);
```

### Functional\pick()
Pick a single element from a collection of objects or an array by index.
If no such index exists, return the default value.

``array Functional\pick(array|Traversable $collection, mixed $propertyName, mixed $default, callable $callback)`` 

```php
<?php
use function Functional\pick;

$array = ['one' => 1, 'two' => 2, 'three' => 3];
pick($array, 'one'); //return 1;
pick($array, 'ten'); //return null;
pick($array, 'ten', 10); //return 10;
```

### Functional\pluck()
Fetch a single property from a collection of objects or arrays.

``array Functional\pluck(array|Traversable $collection, string|integer|float|null $propertyName)`` 

```php
<?php
use function Functional\pluck;

$names = pluck($users, 'name');
```

### Functional\partition()
Splits a collection into two by callback. Truthy values come first

``array Functional\partition(array|Traversable $collection, callable $callback)`` 

```php
<?php
use function Functional\partition;

list($admins, $users) = partition($collection, function($user) {
    return $user->isAdmin();
});
```

###Functional\group()
Splits a collection into groups by the index returned by the callback

``array Functional\group(array|Traversable $collection, callable $callback)`` 

```php
<?php
use function Functional\group;

$groupedUser = group($collection, function($user) {
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
use function Functional\reduce_left;
use function Functional\reduce_right;

// $str will be "223"
$str = reduce_left([2, 3], function($value, $index, $collection, $reduction) {
    return $reduction . $value;
}, 2);

// $str will be "232"
$str = reduce_right([2, 3], function($value, $index, $collection, $reduction) {
    return $reduction . $value;
}, 2);
```

### Functional\flatten()
Takes a nested combination of collections and returns their contents as a single, flat array. Does not preserve indexes.

``array Functional\flatten(array|Traversable $collection)`` 

```php
<?php
use function Functional\flatten;

$flattened = flatten([1, 2, 3, [1, 2, 3, 4], 5]);
// [1, 2, 3, 1, 2, 3, 4, 5];
```

### Functional\first_index_of()
Returns the first index holding specified value in the collection. Returns false if value was not found

``array Functional\first_index_of(array|Traversable $collection, mixed $value)`` 

```php
<?php
use function Functional\first_index_of;

// $index will be 0
$index = first_index_of(['value', 'value'], 'value');
```

### Functional\last_index_of()
Returns the last index holding specified value in the collection. Returns false if value was not found

``array Functional\last_index_of(array|Traversable $collection, mixed $value)`` 

```php
<?php
use function Functional\last_index_of;

// $index will be 1
$index = last_index_of(['value', 'value'], 'value');
```

### Functional\true() / Functional\false()
Returns true or false if all elements in the collection are strictly true or false

``bool Functional\true(array|Traversable $collection)`` 
``bool Functional\false(array|Traversable $collection)`` 

```php
<?php
use function Functional\true;
use function Functional\false;

// Returns true
true([true, true]);
// Returns false
true([true, 1]);

// Returns true
false([false, false, false]);
// Returns false
false([false, 0, null, false]);
```

### Functional\truthy() / Functional\falsy()
Returns true or false if all elements in the collection evaluate to true or false

``bool Functional\truthy(array|Traversable $collection)`` 
``bool Functional\falsy(array|Traversable $collection)`` 

```php
<?php
use function Functional\truthy;
use function Functional\falsy;

// Returns true
truthy([true, true, 1, 'foo']);
// Returns false
truthy([true, 0, false]);

// Returns true
falsy([false, false, 0, null]);
// Returns false
use function Functional\truthy;falsy([false, 'str', null, false]);
```

### Functional\contains()
Returns true if given collection contains given element. If third parameter is true, the comparison
will be strict

``bool Functional\contains(array|Traversable $collection, mixed $value[, bool $strict = true])`` 

```php
<?php
use function Functional\contains;

// Returns true
contains(['el1', 'el2'], 'el1');

// Returns false
contains(['0', '1', '2'], 2);
// Returns true
contains(['0', '1', '2'], 2, false);
```

### Functional\zip()
Recombines arrays by index and applies a callback optionally

``array Functional\zip(array|Traversable $collection1[, array|Traversable ...[, callable $callback]])`` 

```php
<?php
use function Functional\zip;

// Returns [['one', 1], ['two', 2], ['three', 3]]
zip(['one', 'two', 'three'], [1, 2, 3]);

// Returns ['one|1', 'two|2', 'three|3']
zip(
    ['one', 'two', 'three'],
    [1, 2, 3],
    function($one, $two) {
        return $one . '|' . $two;
    }
);
```

### Functional\with()
Invoke a callback on a value if the value is not null

```php
<?php
use function Functional\with;

with($value, function($value) {
    $this->doSomethingWithValue($value);
});
```

### Functional\sort()
Sorts a collection with a user-defined function, optionally preserving array keys

```php
<?php
use function Functional\sort;

// Sorts a collection alphabetically
sort($collection, function($left, $right) {
    return strcmp($left, $right);
});

// Sorts a collection alphabetically, preserving keys
sort($collection, function($left, $right) {
    return strcmp($left, $right);
}, true);

// Sorts a collection of users by age
sort($collection, function($user1, $user2) {
    if ($user1->getAge() == $user2->getAge()) {
        return 0;
    }

    return ($user1->getAge() < $user2->getAge()) ? -1 : 1;
});
```

### Functional\retry()
Retry a callback until the number of retries are reached or the callback does no longer throw an exception

```php
use function Functional\retry;
use function Functional\sequence_exponential;

assert_options(ASSERT_CALLBACK, function () {throw new Exception('Assertion failed');});

// Assert that a file exists 10 times with an exponential back-off
retry(
    function() {assert(file_exists('/tmp/lockfile'));},
    10,
    sequence_exponential(1, 100)
);
```

### Functional\poll()
Retry a callback until it returns a truthy value or the timeout (in microseconds) is reached

```php
use function Functional\poll;
use function Functional\sequence_linear;

// Poll if a file exists for 10,000 microseconds with a linearly growing back-off starting at 100 milliseconds
poll(
    function() {
        return file_exists('/tmp/lockfile');
    },
    10000,
    sequence_linear(100, 1)
);
```

You can pass any `Traversable` as a sequence for the delay but Functional comes with `Functional\sequence_constant()`, `Functional\sequence_linear()` and `Functional\sequence_exponential()`.

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


`mixed Functional\memoize(callable $callback[, array $arguments = []], [mixed $key = null]])` 
Returns and stores the result of the function call. Second call to the same function will return the same result without calling the function again


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
