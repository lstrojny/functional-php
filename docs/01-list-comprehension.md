 - [Overview](00-index.md)
 - Chapter 1: list comprehension
 - [Chapter 2: partial application](02-partial-application.md)
 - [Chapter 3: access functions](03-access-functions.md)
 - [Chapter 4: function functions](04-function-functions.md)
 - [Chapter 5: mathematical functions](05-mathematical-functions.md)
 - [Chapter 6: transformation functions](06-transformation-functions.md)
 - [Chapter 7: miscellaneous](07-miscellaneous.md)

# Function overview


## every() & invoke()

``Functional\every(array|Traversable $collection, callable $callback)``  

```php
<?php
use function Functional\every;
use function Functional\invoke;

// If all users are active, set them all inactive
if (every($users, function($user, $collectionKey, $collection) {return $user->isActive();})) {
    invoke($users, 'setActive', [false]);
}
```


## some()

``bool Functional\some(array|Traversable $collection, callable $callback)``   

```php
<?php
use function Functional\some;

if (some($users, function($user, $collectionKey, $collection) use($me) {return $user->isFriendOf($me);})) {
    // One of those users is a friend of me
}
```


## none()

``bool Functional\none(array|Traversable $collection, callable $callback)``   

```php
<?php
use function Functional\none;

if (none($users, function($user, $collectionKey, $collection) {return $user->isActive();})) {
    // Do something with a whole list of inactive users
}
```


## reject() & select()

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


## drop_first() & drop_last()

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


## true() & false()
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


## truthy() & falsy()
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
falsy([false, 'str', null, false]);
```


## contains()
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


## sort()
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


## Other:

`void Functional\each(array|Traversable $collection, callable $callback)`   
Applies a callback to each element


`array Functional\map(array|Traversable $collection, callable $callback)`   
Applies a callback to each element in the collection and collects the return value


`mixed Functional\first(array|Traversable $collection[, callable $callback])`   
`mixed Functional\head(array|Traversable $collection[, callable $callback])`   
Returns the first element of the collection where the callback returned true. If no callback is given, the first element
is returned


`mixed Functional\last(array|Traversable $collection[, callable $callback])`  
Returns the last element of the collection where the callback returned true. If no callback is given, the last element
is returned


`mixed Functional\tail(array|Traversable $collection[, callable $callback])`  
Returns every element of the collection except the first one. Elements are optionally filtered by callback.
