 - [Overview](00-index.md)
 - [Chapter 1: list comprehension](01-list-comprehension.md)
 - Chapter 2: partial application
 - [Chapter 3: access functions](03-access-functions.md)
 - [Chapter 4: function functions](04-function-functions.md)
 - [Chapter 5: mathematical functions](05-mathematical-functions.md)
 - [Chapter 6: transformation functions](06-transformation-functions.md)
 - [Chapter 7: miscellaneous](07-miscellaneous.md)

# Partial application

## Introduction

Partial application is a concept where a higher-order function returns a new function by applying the passed arguments
to the new function. Let’s have a look at the following simple function that takes two parameters and subtracts them:

```php
$subtractor = function ($a, $b) {
    return $a - $b;
};
$subtractor(10, 20); // -> -10
```

The same function can be reduced to two nested functions each with a single argument:

```php
$subtractor = function ($a) {
    return function ($b) use ($a) {
        return $a - $b;
    };
}
$partiallyAppliedSubtractor = $subtractor(10);
$partiallyAppliedSubtractor(20); // -> -10
```


## partial_left() & partial_right()

`Functional\partial_left()` and `Functional\partial_right` are shortcuts to create partially applied functions. Let’s revisit
our example again, this time using `partial_left`:

```php
use function Functional\partial_left;

$partiallyAppliedSubtractor = partial_left($subtractor, 10);
$partiallyAppliedSubtractor(20); // -> -10
```

A slightly different example with `partial_right` where we do the calculation `20 - 10`:

```php
use function Functional\partial_right;

$partiallyAppliedSubtractor = partial_right($subtractor, 10);
$partiallyAppliedSubtractor(20); // -> 10
```

## partial_any()

There is a third function in the family called `partial_any`. Unlike its siblings it doesn’t automatically merge but it
only resolves placeholders that can either be indicated by calling `Functional\placeholder()`, `Functional\…()` or the
constant `Functional\…` As a subtraction function is kind of useless, let’s do something more practical and use partial 
application in combination with `select` to find all elements that contain `jo`:

```php
use function Functional\select;

$elements = [
    'john',
    'joe',
    'joanna',
    'patrick',
];
$selected = select($elements, function ($element) {
    return substr_count($element, 'jo');
});
```

Instead of writing that slightly obnoxious callback, let’s use a partially applied function:

```php
use function Functional\select;
use function Functional\partial_any;
use const Functional\…;

$elements = [
    'john',
    'joe',
    'joanna',
    'patrick',
];
$selected = select($elements, partial_any('substr_count', …, 'jo'));
```


## partial_method()

The fourth member of the partial application family is the `partial_method` function. It returns a function with a bound
method call expecting the object that receives the method call as a first parameter. Let’s assume we want to filter a
list of objects by a predicate that belongs to the object:

```php
use function Functional\select;
use function Functional\partial_method;

$users = [new User(), new User()];
$registeredUsers = select($users, function (User $user) {
    return $user->isRegistered();
});
```

We can rewrite the above example like this:

```php
use function Functional\select;
use function Functional\partial_method;

$users = [new User(), new User()];
$registeredUsers = select($users, partial_method('isRegistered'));
```
