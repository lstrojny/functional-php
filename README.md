Functional PHP: Functional primitives for PHP
=============================================



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


