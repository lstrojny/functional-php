# Functional PHP: Functional primitives for PHP

A set of functional primitives for PHP, heavily inspired by [Scala’s traversable
collection](http://www.scala-lang.org/archives/downloads/distrib/files/nightly/docs/library/scala/collection/Traversable.html),
[Dojo’s array functions](http://dojotoolkit.org/reference-guide/quickstart/arrays.html) and
[Underscore.js](http://documentcloud.github.com/underscore/)

  - Works with arrays and everything implementing interface `Traversable`
  - Consistent interface: first parameter is always the collection, than the callback. Callbacks always receives value, key,
    collection
  - Calls 5.3 closures as well as traditional callbacks
  - C implementation for performance but compatible userland implementation is packaged if you can’t install PHP
    extensions
  - All functions reside in namespace `Functional` to not conflict with any other extension or library


!["Start using Functional PHP"](http://memegenerator.net/cache/instances/400x/10/11239/11509601.jpg)

## TODO
 - Add iterator based generators: `range()`, `repeat()`, `cycle()`, `ìncrement()`, `limit()`
 - Add `concat(array1, array2, ...)`, `average()`


## Installation


### Install native extension
```bash
cd functional-php/extension/
phphize
./configure
make
sudo make install
```


### Use userland extension
```php
<?php
include 'path/to/functional-php/src/Functional/_import.php';
```

Everytime you want to work with Functional PHP and not reference the fully qualified name, add `use Functional as F;` on top of
your PHP file.


## Overview


### Functional\every() & Functional\invoke()

``Functional\every(array|Traversable $collection, callable $callback)``

``bool Functional\invoke(array|Traversable $collection, string $methodName[, array $methodArguments])``

```php
<?php
use Functional as F;

// If all users are active, set them all inactive
if (F\every($users, function($user, $collectionKey, $collection) {return $user->isActive();})) {
    F\invoke($users, 'setActive', array(false));
}
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

### Functional\drop_first() & Functional\drop_last()

``array Functional\drop_first(array|Traversable $collection, callable $callback)``

``array Functional\drop_last(array|Traversable $collection, callable $callback)``

```php
<?php
use Functional as F;

$fn = function($user, $key, $collection) {
    return $key == 3;
};

// All users except the first three
F\drop_first($users, $fn);
// First three users
F\drop_last($users, $fn);
```

### Functional\pluck()
Fetch a single property from a collection of objects or arrays.

``array Functional\pluck(array|Traversable $collection, string $propertyName)``

```php
<?php
use Functional as F;

$names = F\pluck($users, 'name');
```

### Functional\partition()
Splits a collection into two by callback. Thruthy values come first

``array Functional\partition(array|Traversable $collection, callable $callback)``

```php
<?php
use Functional as F;

list($admins, $users) = F\partition($collection, function($user) {
    return $user->isAdmin();
});
```

###Functional\group()
Splits a collection into groups by the key returned by the callback

``array Functional\group(array|Traversable $collection, callable $callback)``

```php
<?php
use Functional as F;

$groupedUser = F\group($collection, $function($user) {
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

// $sum will be 64 (2^2^3)
$sum = F\reduce_left(array(2, 3), function($value, $key, $collection, $reduction) {
    return $reduction ^ $value;
}, 2);

// $sum will be 512 (2^3^2)
$sum = F\reduce_right(array(2, 3), function($value, $key, $collection, $reduction) {
    return $reduction ^ $value;
}, 2);
```

### Functional\flatten()
```php
<?php
use Functional as F;

$flattened = F\flatten(array(1, 2, 3, array(1, 2, 3, 4), 5));
// array(1, 2, 3, 1, 2, 3, 4, 5);
```

### Additional functions:

`void Functional\each(array|Traversable $collection, callable $callback)`

   Applies a callback to each element

`array Functional\map(array|Traversable $collection, callable $callback)`

   Applies a callback to each element in the collection and collects the return value

`mixed Functional\first(array|Traversable $collection, callable $callback)`

   Returns the first element of the collection where the callback returned true

`mixed Functional\last(array|Traversable $collection, callable $callback)`

   Returns the last element of the collection where the callback returned true

`integer|float Functional\product(array|Traversable $collection, $initial = 1)`

   Calculates the product of all elements, starting with `$initial`

`integer|float Functional\ratio(array|Traversable $collection, $initial = 1)`

   Calculates the ratio of all elements, starting with `$initial`

`integer|float Functional\sum(array|Traversable $collection, $initial = 0)`

   Calculates the sum of all elements, starting with `$initial`

`integer|float Functional\difference(array|Traversable $collection, $initial = 0)`

   Calculates the difference of all elements, starting with `$initial`

`array Functional\unique(array|Traversable $collection[, callback $indexer])`

   Returns a unified array based on the index value returned by the callback

`mixed Functional\maximum(array|Traversable $collection)`

   Returns the highest element in the array

`mixed Functional\minimum(array|Traversable $collection)`

   Returns the lowest element in the array

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
 - [Max Beutel](https://github.com/maxbeutel) for `Functional\unique()`
