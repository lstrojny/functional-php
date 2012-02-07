# Functional PHP: Functional primitives for PHP
[![Build Status](https://secure.travis-ci.org/lstrojny/functional-php.png)](http://travis-ci.org/lstrojny/functional-php)


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

## Navigation

[Installation][] | [Functions][] | [Data structures][] 


## TODO
 - Add iterator based generators: `range()`, `repeat()`, `cycle()`, `ìncrement()`, `limit()`
 - Add `concat(array1, array2, ...)`, `drop_while()`, `sort()`, `split_at()`, `slice()`, `without()`, `intersect()`
 - Reverse iterate for `last_index_of()`
 - Fix performance of C impl. for iterators and `last_index_of()`/`first_index_of()`
 - Fix performance of C impl. for hash iterators and `drop_first()`

<a name='installation'></a>
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
```php
<?php
include 'path/to/functional-php/src/Functional/_import.php';
```

Everytime you want to work with Functional PHP and not reference the fully qualified name, add `use Functional as F;` on top of
your PHP file.


<a name='functions'></a>
## Functions


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

$fn = function($user, $index, $collection) {
    return $index === 3;
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
Splits a collection into groups by the index returned by the callback

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
$sum = F\reduce_left(array(2, 3), function($value, $index, $collection, $reduction) {
    return $reduction ^ $value;
}, 2);

// $sum will be 512 (2^3^2)
$sum = F\reduce_right(array(2, 3), function($value, $index, $collection, $reduction) {
    return $reduction ^ $value;
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
Returns the first index holding specified value in the ccollection. Returns false if value was not found

``array Functional\first_index_of(array|Traversable $collection, mixed $value)``

```php
<?php
use Functional as F;

// $index will be 0
$index = F\first_index_of(array('value', 'value'), 'value');
```

### Functional\last_index_of()
Returns the last index holding specified value in the ccollection. Returns false if value was not found

``array Functional\last_index_of(array|Traversable $collection, mixed $value)``

```php
<?php
use Functional as F;

// $index will be 1
$index = F\first_index_of(array('value', 'value'), 'value');
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

``bool Functional\contains(array|Traversable $collection, mixed $value[, bool $strict = false])``

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
Functional\zip(array('one', 'two', 'three'), array(1, 2, 3));

// Returns array('one|1', 'two|2', 'three|3')
Functional\zip(
    array('one', 'two', 'three'),
    array(1, 2, 3),
    function($one, $two) {
        return $one . '|' . $two;
    }
);
```


### Additional functions:

`void Functional\each(array|Traversable $collection, callable $callback)`  
Applies a callback to each element


`array Functional\map(array|Traversable $collection, callable $callback)`  
Applies a callback to each element in the collection and collects the return value


`mixed Functional\first(array|Traversable $collection[, callable $callback])`  
Returns the first element of the collection where the callback returned true. If no callback is given, the first element
is returned


`mixed Functional\last(array|Traversable $collection[, callable $callback])`  
Returns the last element of the collection where the callback returned true. If no callback is given, the last element
is returned


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
Returns a unified array based on the index value returned by the callback, use `$strict` to change comparision mode


`mixed Functional\maximum(array|Traversable $collection)`  
Returns the highest element in the array or collection


`mixed Functional\minimum(array|Traversable $collection)`  
Returns the lowest element in the array or collection

<a name='data_structures'></a>
## Data structures

Functional data structures are immutable.
They may implement typeclasses such as `Monoid`, `Functor` or `Monad`. 

### Option

> Tony Hoare introduced Null references in ALGOL W back in 1965 “simply because it was so easy to implement”. He now talks about that decision considering it “my billion-dollar mistake”. (source: http://www.infoq.com/presentations/Null-References-The-Billion-Dollar-Mistake-Tony-Hoare) 

Option lets you get rid of `null`. They represent optional values. 
Instances of Option are either an instance of Some(value) or None().
You can think of them as arrays of zero or one element only.

Consider the following code:

```php
<?php
$user = $db->findUser($id); // can return null
if ($user) {
  $name = $user->getName();
} else {
  $name = 'Anonymous';
}
```

We have to deal with the possibility that `$db->findUser($id)` returns a `null`.
Instead, we could use map to propagate the possible absence of user:

```php
<?php
$nameOption = option($db->findUser($id))->map(function($user) {
  return $user->getName();
});
```

Now `$name` is either a `Some(name)` or a `None()`. We can extract the name by providing a default value:

```php
<?php
$name = $nameOption->getOrElse('Anonymous');
```

In fact, the best would be that `$db->findUser($id)` returns an Option of User, instead of a User or null.

### Construct an option

```php
<?php
$someInt     = new Some(12);
$none        = new None();

$maybeInt    = option(12);    // Some(12)
$maybeString = option("foo"); // Some("foo")
$maybeBool   = option(true);  // Some(true)
$maybeBool   = option(false); // Some(false)
$maybe       = option();      // None()
$maybe       = option(null);  // None()
```

### Functor support

Options act as functor: they provide the `map` function.

`map` signature is `Functor[A] => (A => B) => Functor[B]`.

```php
<?php
$id  = function($x) { return $x; };
$inc = function($x) { return $x + 1; };

option(42)->map($id);  // Some(42)
option(42)->map($inc); // Some(43)
option()->map($inc);   // None
```

### Monad support

Options act as monads: they provide the `bind` function.

`bind` signature is `Monad[A] => (A => Monad[B]) => Monad[B]`.

```php
<?php
// returns a Some($x)
$someId     = function($x) { return option($x); };

// returns a Some($y) or None() if $x < 0
$squareRoot = function($x) { return option($x >= 0 ? sqrt($x) : null); }

option(9)->bind($someId);      // Some(9)
option(9)->bind($squareRoot);  // Some(3)
option(-2)->bind($squareRoot); // None
option()->bind($squareRoot);   // None
```

### Semigroup support

Options act as semigroup: they provide the `append` function.

```php
<?php
option(42)->append(option(10))       // Some(52)
option("foo")->append(option("bar")) // Some("foobar")
option("foo")->append(option())      // Some("foo")
option()->append(option("bar"))      // Some("bar")
option()->append(option())           // None
```

`append` natively supports numbers, booleans and arrays.
You can add support for more types by passing a callback function:

```php
<?php
option(10)->append(option(2), function($x, $y) { return $x * $y; }) // 20
```

### Iterator support

You can iterate on options as if they were an array of zero or one element.

```php
<?php
// prints "hello"
foreach(option("hello") as $x) {
  echo $x;
}
// does nothing
foreach(option() as $x) {
  die("I won't get there at all!");
}
```

### Catamorphism

Options support catamorphism:

```php
<?php
$double = function($x) { return $x * 2; };
$zero   = function() { return 0; };

option(42)->fold($double, $zero) // 84
option()->fold($double, $zero)   // 0
```

### Other Option functions

```php
<?php
option(42)->getOrElse(33) // 42
option()->getOrElse(33)   // 33

option(42)->get()         // 42
option()->get()           // null

option(42)->isEmpty()     // false
option()->isEmpty()       // true

option(42)->isNotEmpty()  // true
option()->isNotEmpty()    // false

option(42)->toArray()     // array(42)
option()->toArray()       // array()
```

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
 - The people behind [Travis CI](http://travis-ci.org/) for continous integration

[Installation]: #installation
[Functions]: #functions
[Data Structures]: #data_structures
