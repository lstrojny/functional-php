Functional PHP: Functional primitives for PHP
=============================================

  - Works with arrays and everything implementing interface Traversable
  - Consistent interface: first parameter is always the collection, than the callback. Callbacks always get value, key,
    collection passed
  - Calls 5.3 closures as well as traditional callbacks
  - C implementation for performance

Functional\all() & Functional\invoke()
--------------------------------------
```php
<?php
use Functional;

// If all users are active, set them all inactive
if (all($users, function($user) {return $user->isActive();})) {
    invoke($users, 'setActive', array(true));
}
```


Functional\any()
----------------
```php
<?php
use Functional;

if (any($users, function($user) use($me) {return $user->isFriendOf($me);})) {
    // One of those users is a friend of me
}
```


Functional\none()
-----------------
```php
<?php
use Functional;

if (none($users, function($user) {return $user->isActive();})) {
    // Do something with a whole list of inactive users
}
```


Functional\reject() & Functional\select()
-----------------------------------------
```php
<?php
use Functional;

$fn = function($user) {
    return $user->isActive();
};
$activeUsers = select($users, $fn);
$inactiveUsers = reject($users, $fn);
```


Functional\pluck()
------------------
Fetch a single property from a collection of objects:

```php
<?php
use Functional;

$names = pluck($users, 'names');
```


Additional functions:
---------------------

 - Functional\each(): Applies a callback to each element
 - Functional\map(): Applies a callback to each element in the array and collects the return value
