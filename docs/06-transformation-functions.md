 - [Overview](00-index.md)
 - [Chapter 1: list comprehension](01-list-comprehension.md)
 - [Chapter 2: partial application](02-partial-application.md)
 - [Chapter 3: access functions](03-access-functions.md)
 - [Chapter 4: function functions](04-function-functions.md)
 - [Chapter 5: mathematical functions](05-mathematical-functions.md)
 - Chapter 6: transformation functions
 - [Chapter 7: miscellaneous](07-miscellaneous.md)
 
# Transformation functions


## partition()
Splits a collection into two by callback. Truthy values come first

``array Functional\partition(array|Traversable $collection, callable $callback)``  

```php
<?php
use function Functional\partition;

list($admins, $users) = partition($collection, function($user) {
    return $user->isAdmin();
});
```


## group()
Splits a collection into groups by the index returned by the callback

``array Functional\group(array|Traversable $collection, callable $callback)``  

```php
<?php
use function Functional\group;

$groupedUser = group($collection, function($user) {
    return $user->getGroup()->getName();
});
```


## zip()
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


## flatten()
Takes a nested combination of collections and returns their contents as a single, flat array. Does not preserve indexes.

``array Functional\flatten(array|Traversable $collection)``  

```php
<?php
use function Functional\flatten;

$flattened = flatten([1, 2, 3, [1, 2, 3, 4], 5]);
// [1, 2, 3, 1, 2, 3, 4, 5];
```


## reduce_left() & reduce_right()
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


## Other

`array Functional\unique(array|Traversable $collection[, callback $indexer[, bool $strict = true]])`  
Returns a unified array based on the index value returned by the callback, use `$strict` to change comparison mode


`array Functional\flat_map(array|Traversable $collection, callable $callback)`  
Applies a callback to each element in the collection and collects the return values flattening one level of nested arrays.
