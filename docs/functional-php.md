# Functional PHP

<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->
**Contents**

  - [General](#general)
    - [Import functions](#import-functions)
    - [Example](#example)
- [Function overview](#function-overview)
  - [every() & invoke()](#every--invoke)
  - [some()](#some)
  - [none()](#none)
  - [reject() & select()](#reject--select)
  - [drop_first() & drop_last()](#drop_first--drop_last)
  - [true() & false()](#true--false)
  - [truthy() & falsy()](#truthy--falsy)
  - [contains()](#contains)
  - [sort()](#sort)
  - [Other](#other)
- [Partial application](#partial-application)
  - [Introduction](#introduction)
  - [partial_left() & partial_right()](#partial_left--partial_right)
  - [ary()](#ary)
  - [partial_any()](#partial_any)
  - [partial_method()](#partial_method)
  - [converge()](#converge)
- [Currying](#currying)
  - [curry()](#curry)
  - [curry_n()](#curry_n)
- [Access functions](#access-functions)
  - [with()](#with)
  - [invoke_if()](#invoke_if)
  - [invoke()](#invoke)
  - [invoke_first() & invoke_last()](#invoke_first--invoke_last)
  - [invoker()](#invoker)
  - [pluck()](#pluck)
  - [pick()](#pick)
  - [first_index_of()](#first_index_of)
  - [last_index_of()](#last_index_of)
  - [indexes_of()](#indexes_of)
  - [select_keys()](#select_keys)
  - [omit_keys()](#omit_keys)
  - [take_left()](#take_left)
  - [take_right()](#take_right)
- [Function functions](#function-functions)
  - [retry()](#retry)
  - [poll()](#poll)
  - [capture()](#capture)
  - [compose()](#compose)
  - [tail_recursion()](#tail_recursion)
  - [flip()](#flip)
  - [not](#not)
  - [Other](#other-1)
- [Mathematical functions](#mathematical-functions)
- [Transformation functions](#transformation-functions)
  - [partition()](#partition)
  - [group()](#group)
  - [zip() & zip_all()](#zip--zip_all)
  - [flatten()](#flatten)
  - [reduce_left() & reduce_right()](#reduce_left--reduce_right)
  - [intersperse()](#intersperse)
  - [Other](#other-2)
- [Conditional functions](#conditional-functions)
  - [if_else()](#if_else)
  - [match()](#match)
- [Higher order comparison functions](#higher-order-comparison-functions)
  - [compare_on & compare_object_hash_on](#compare_on--compare_object_hash_on)
- [Miscellaneous](#miscellaneous)
  - [concat()](#concat)
  - [const_function()](#const_function)
  - [id()](#id)
  - [tap()](#tap)
  - [repeat()](#repeat)
  - [noop()](#noop)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

## General

### Import functions

Whenever you want to work with Functional PHP and not reference the fully qualified name, add `use Functional as F;` on
top of your PHP file or use `use function Functional\function_name`. The latter is used in the documentation is the
preferred way starting with PHP 5.6.

### Example

```php
use function Functional\map;

$emails = map($users, fn ($user) => $users->getEmail());
```

# Function overview


## every() & invoke()

``Functional\every(array|Traversable $collection, callable $callback = null)``

```php
use function Functional\every;

$allUsersAreActive = every($users, fn ($user, $key, $collection) => $user->isActive());
```

If `$callback` is not provided then the `id()` function is used and `every` will return true if every value in the collection is truthy.

## some()

``bool Functional\some(array|Traversable $collection, callable $callback = null)``

```php
use function Functional\some;

$iHaveAtLeastOneFriend = some($users, fn ($user, $key, $collection) => $user->isFriendOf($me));
```

If `$callback` is not provided then the `id()` function is used and `some` will return true if at least one value in the collection is truthy.

## none()

``bool Functional\none(array|Traversable $collection, callable $callback)``

```php
use function Functional\none;

$noUsersAreActive = none($users, fn ($user, $key, $collection) => $user->isActive());
```

If `$callback` is not provided then the `id()` function is used and `none` will return true if every value in the collection is falsey.

## reject() & select()

``array Functional\select(array|Traversable $collection, callable $callback = null)``

``array Functional\reject(array|Traversable $collection, callable $callback = null)``

```php
use function Functional\select;
use function Functional\reject;

$fn = fn ($user, $key, $collection) => $user->isActive();
$activeUsers = select($users, $fn);
$inactiveUsers = reject($users, $fn);
```

For both functions array keys are preserved.

For both functions if a value for $callback is not provided then the `id()` function is used. For `select`, this means that only the truthy values in the collection will be returned. For `reject`, this means that only the falsey values in the collection will be returned.

Alias for `Functional\select()` is `Functional\filter()`

**Note:** This may unexpectedly turn your indexed array into an associative array, [see here](https://github.com/lstrojny/functional-php/issues/39#issuecomment-48034617) if you always want to keep an indexed array.


## drop_first() & drop_last()

``array Functional\drop_first(array|Traversable $collection, callable $callback)``

``array Functional\drop_last(array|Traversable $collection, callable $callback)``

```php
use function Functional\drop_first;
use function Functional\drop_last;

$fn = fn ($user, $index, $collection) => $index < 3;
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
use function Functional\sort;

// Sorts a collection alphabetically
sort($collection, fn ($left, $right) => strcmp($left, $right));

// Sorts a collection alphabetically, preserving keys
sort($collection, fn ($left, $right) => strcmp($left, $right), true);

// Sorts a collection of users by age
sort($collection, function ($user1, $user2) {
    if ($user1->getAge() == $user2->getAge()) {
        return 0;
    }

    return ($user1->getAge() < $user2->getAge()) ? -1 : 1;
});
```


## Other

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

`array Functional\reindex(array|Traversable $collection, callable $callback)`
Returns the collection reindexed with keys generated by a callback applied to each element.

# Partial application

## Introduction

Partial application is a concept where a higher-order function returns a new function by applying the passed arguments
to the new function. Let’s have a look at the following simple function that takes two parameters and subtracts them:

```php
$subtractor = fn ($a, $b) => $a - $b;
$subtractor(20, 10); // 10
```

The same function can be reduced to two nested functions each with a single argument:

```php
$subtractor = fn ($a) => fn ($b) => $a - $b;
$partiallyAppliedSubtractor = $subtractor(20);
$partiallyAppliedSubtractor(10); // 10
```


## partial_left() & partial_right()

`Functional\partial_left()` and `Functional\partial_right` are shortcuts to create partially applied functions. Let’s revisit
our example again, this time using `partial_left`:

```php
use function Functional\partial_left;

$partiallyAppliedSubtractor = partial_left($subtractor, 20);
$partiallyAppliedSubtractor(10); // 10
```

A slightly different example with `partial_right` where we do the calculation `20 - 10`:

```php
use function Functional\partial_right;

$partiallyAppliedSubtractor = partial_right($subtractor, 20);
$partiallyAppliedSubtractor(10); // 10
```

## ary()

`Functional\ary` (as in arity) takes a `callable` and a count and calls the `callable` with 
that many arguments using `take_left` if positive, or `take_right` if negative. 
Throws if passed 0.

For example:

```php
use function Functional\ary;
use function Functional\map;

// This fails because map calls its callable with the element, index and the whole collection
// map($array, 'ucfirst');

// Using `ary`
map($array, ary('ucfirst', 1)); // Passes only the first argument to `ucfirst`
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

$selected = select($elements, fn ($name) => substr_count($name, 'jo'));
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

$registeredUsers = select($users, fn ($user) => $user->isRegistered());
```

We can rewrite the above example like this:

```php
use function Functional\select;
use function Functional\partial_method;

$registeredUsers = select($users, partial_method('isRegistered'));
```

## converge()

``callable Functional\converge(callable $convergingFunction, callable[] branchingFunctions)``

`converge` accepts a converging function and a list of branching functions and returns a new function.

The returned function takes a variable number of arguments.

The _converging function_ should take the same number of arguments as there are branching functions.

Each _branching function_ should take the same number of arguments as the number of arguments passed in to the returned function.

```php
use function Functional\converge;

function div($dividend, $divisor) {
    return $dividend / $divisor;
}

$average = converge('div', ['array_sum', 'count']);
$average([1, 2, 3, 4]); // 2.5
```

The returned function, in the above example it is named `$average`, passes each of its arguments to each branching function. `$average` then takes the return values of all the branching functions and passes each one as an argument to the converging function. The return value of the converging function is the return value of `$average`.


# Currying

Currying is similar to and often confused with partial application. But instead of binding parameters to some value and returning a new function, a curried function will take one parameter on each call and return a new function until all parameters are bound.

Currying can be seen as partially applying one parameter after the other.

## curry()

If we revisit the example used for partial application, the curried version would be :

```php
use function Functional\curry;

$curriedSubtractor = curry($subtractor);
$subtractFrom10 = $curriedSubtractor(20);
$subtractFrom10(10); // 10
```

The difference becomes more salient with functions taking more than two parameters :

```php
use function Functional\curry;

$curriedAdd = curry(fn ($a, $b, $c, $d) => $a + $b + $c + $d);

$add10 = $curriedAdd(10);
$add15 = $add10(5);
$add42 = $add15(27);
$add42(10); // 52
```

Since PHP allows for optional parameters, you can decide if you want to curry them or not. The default is to not curry them.

```php
use function Functional\curry;

$add = fn ($a, $b, $c = 10) => $a + $b + $c;

// Curry only required parameters. The default $c will always be 10.
$curriedAdd = curry($add, true);

// This time, 3 parameters will be curried.
$curriedAddWithOptional = curry($add, false);
```

Starting with PHP7 and the implementation of the ["Uniform variable syntax"](https://wiki.php.net/rfc/uniform_variable_syntax), you can greatly simplify the usage of curried functions.

```php
use function Functional\curry;

$curriedAdd = curry(fn ($a, $b, $c, $d) => $a + $b + $c + $d);
$curriedAdd(10)(5)(27)(10); // 52
```

_Note, that you cannot use `curry` on a flipped function. `curry` uses reflection to get the number of function arguments, but this is not possible on the function returned from `flip`.  Instead use `curry_n` on flipped functions._

## curry_n()

`curry` uses reflection to determine the number of arguments, which can be slow depending on your requirements. Also, you might want to curry only the first parameters, or your function expects a variable number of parameters. In all cases, you can use `curry_n` instead.

```php
use function Functional\curry_n;

$curriedAdd = curry_n(2, fn ($a, $b, $c, $d) => $a + $b + $c + $d);

$add10 = $curriedAdd(10);
$add15 = $add10(5);
$add15(27, 10); // 52
```

Note that if you give a parameter bigger than the real number of parameters of your function, all extraneous parameters will simply be passed but ignored by the original function.

# Access functions

Functional PHP comes with a set of invocation helpers that ease calling function or methods or accessing nested values.


## with()
Invoke a callback on a value if the value is not null.

``Function\with(mixed $value, callable $callback, bool $invokeValue = true, mixed $default = null): mixed``

```php
use function Functional\with;

$retval = with(create_user('John Doe'), function ($user) {
    send_welcome_email($user);

    return 'my_result';
});
```

`with()` returns whatever the callback returns. In the above example `$retval` would be `'my_result'`.

If the value of `$value` is `null`, `with()` will return `$default` which defaults to be `null`.

## invoke_if()

``mixed Functional\invoke_if(mixed $object, string $methodName[, array $methodArguments, mixed $defaultValue])``

```php
use function Functional\invoke_if;

// If $user is an object and has a public method getId(), the method's return value is returned,
// otherwise the default value `0` (4th argument) is returned.
$userId = invoke_if($user, 'getId', [], 0);
```



## invoke()

Invokes method `$methodName` on each object in the `$collection` and returns the results of the call.

``array Functional\invoke(array|Traversable $collection, string $methodName[, array $methodArguments])``

```php
use function Functional\invoke;

// calls addAttendee($user) on each object in $meetings array
invoke($meetings, 'addAttendee', $user); 
```

## invoke_first() & invoke_last()

Invokes method `$methodName` on the first or last object in the `$collection` containing a callable method named `$methodName` and returns the results of the call.

``mixed Functional\invoke_first(array|Traversable $collection, string $methodName[, array $methodArguments])``

``mixed Functional\invoke_last(array|Traversable $collection, string $methodName[, array $methodArguments])``

```php
use function Functional\invoke_first;
use function Functional\invoke_last;

$meetings = [
    new MandatoryEvent(),
    new MandatoryEvent(),
    new OptionalEvent(),
    new MandatoryEvent(),
    new OptionalEvent(),
    new MandatoryEvent(),
]; 

// assuming only OptionalEvents can be delayed/changed...
invoke_first($meetings, 'delayEvent', [30]); // calls delayEvent(30) on $meetings[2]
invoke_last($meetings, 'changeRoom', ['Room 3']); // calls changeRoom('Room 3') on $meetings[4]
```

## invoker()
Returns a function that invokes method `$method` with arguments `$methodArguments` on the object.

``callable Functional\invoker(string $method[, array $methodArguments])``

```php
use function Functional\invoker;

$setLocationToMunich = invoker('updateLocation', ['Munich', 'Germany']);
$setLocationToMunich($user); // calls $user->updateLocation('Munich', 'Germany') 
```

## pluck()
Fetch a single property from a collection of objects or arrays.

``array Functional\pluck(array|Traversable $collection, string|integer|float|null $propertyName)``

```php
use function Functional\pluck;

$names = pluck($users, 'name');
```


## pick()
Pick a single element from a collection of objects or an array by index.
If no such index exists, return the default value.

``array Functional\pick(array|Traversable $collection, mixed $propertyName, mixed $default, callable $callback)``

```php
use function Functional\pick;

$array = ['one' => 1, 'two' => 2, 'three' => 3];
pick($array, 'one'); // 1
pick($array, 'ten'); // null
pick($array, 'ten', 10); // 10
```


## first_index_of()
Returns the first index holding specified value in the collection. Returns false if value was not found

``array Functional\first_index_of(array|Traversable $collection, mixed $value)``

```php
use function Functional\first_index_of;

// $index will be 0
$index = first_index_of(['value', 'value'], 'value');
```


## last_index_of()
Returns the last index holding specified value in the collection. Returns false if value was not found

``array Functional\last_index_of(array|Traversable $collection, mixed $value)``

```php
use function Functional\last_index_of;

// $index will be 1
$index = last_index_of(['value', 'value'], 'value');
```


## indexes_of()
Returns a list of array indexes, either matching the predicate or strictly equal to the the passed value. Returns an empty array if no values were found.

``array Functional\indexes_of(Traversable|array $collection, mixed|callable $value)``

```php
use function Functional\indexes_of;

// $indexes will be array(0, 2)
$indexes = indexes_of(['value', 'value2', 'value'], 'value');
```

## select_keys()

Returns an array containing only those entries in the array/Traversable whose key is in the supplied keys.

```php
use function Functional\select_keys;

// $array will be ['foo' => 1, 'baz' => 3]
$array = select_keys(['foo' => 1, 'bar' => 2, 'baz' => 3], ['foo', 'baz']);
```

## omit_keys()

Returns an array containing only those entries in the array/Traversable whose key is not in the supplied keys.

```php
use function Functional\omit_keys;

// $array will be ['bar' => 2]
$array = omit_keys(['foo' => 1, 'bar' => 2, 'baz' => 3], ['foo', 'baz']);
```

## take_left()

Creates a slice of `$collection` with `$count` elements taken from the beginning. If the collection has less than `$count` elements, the whole collection will be returned as an array.

``array Functional\take_left(Traversable|array $collection, int $count)``

```php
use function Functional\take_left;

take_left([1, 2, 3], 2); // [1, 2]
```

## take_right()

Creates a slice of `$collection` with `$count` elements taken from the end. If the collection has less than `$count` elements, the whole collection will be returned as an array.

This function will reorder and reset the integer array indices by default. This behaviour can be changed by setting `$preserveKeys` to `true`. String keys are always preserved, regardless of this parameter.

``array Functional\take_right(Traversable|array $collection, int $count, bool $preserveKeys = false)``

```php
use function Functional\take_right;

take_right([1, 2, 3], 2); // [2, 3]
take_right(['a', 'b', 'c'], 2, true); // [1 => 'b', 2 => 'c']
```

# Function functions

Function functions take a function or functions and return a new, modified version of the function.


## retry()
Retry a callback until the number of retries are reached or the callback does no longer throw an exception

```php
use function Functional\retry;
use function Functional\sequence_exponential;

assert_options(ASSERT_CALLBACK, fn () => throw new Exception('Assertion failed'));

// Assert that a file exists 10 times with an exponential back-off
retry(
    fn () => assert(file_exists('/tmp/lockfile')),
    10,
    sequence_exponential(1, 100)
);
```


## poll()
Retry a callback until it returns a truthy value or the timeout (in microseconds) is reached

```php
use function Functional\poll;
use function Functional\sequence_linear;

// Poll if a file exists for 10,000 microseconds with a linearly growing back-off starting at 100 milliseconds
poll(
    fn () => file_exists('/tmp/lockfile'),
    10000,
    sequence_linear(100, 1)
);
```

You can pass any `Traversable` as a sequence for the delay but Functional comes with `Functional\sequence_constant()`, `Functional\sequence_linear()` and `Functional\sequence_exponential()`.

## capture()
Return a new function that captures the return value of $callback in $result and returns the callbacks return value

```php
use function Functional\capture;

$fn = capture(fn () => 'Hello world', $result);
$fn();
var_dump($result); // 'Hello world'
```


## compose()
Return a new function that composes multiple functions into a single callable

```php
use function Functional\compose;

$plus2 = fn ($x) => $x + 2;
$times4 = fn ($x) => $x * 4;

$composed = compose($plus2, $times4);
array_map($composed, [1, 2, 5, 8]); // [12, 16, 28, 40]
```



## tail_recursion()
Return an new function that decorates given function with tail recursion optimization using trampoline


```php
use function Functional\tail_recursion;

$sumOfRange = tail_recursion(function ($from, $to, $acc = 0) use (&$sumOfRange) {
    if ($from > $to) {
        return $acc;
    }
    
    return $sumOfRange($from + 1, $to, $acc + $from);
});

$sumOfRange(1, 10000); // 50005000;
```

## flip()
Return a new function with the argument order flipped. This can be useful when currying functions like `filter` to provide the data last.

```php
use function Functional\flip;
use function Functional\curry;

$filter = curry(flip('Functional\filter'));
$getEven = $filter(fn ($number) => $number % 2 === 0);
$getEven([1, 2, 3, 4]); // [2, 4]
```

_Note, that you cannot use `curry` on a flipped function. `curry` uses reflection to get the number of function arguments, but this is not possible on the function returned from `flip`.  Instead use `curry_n` on flipped functions._

## not
Return a new function which takes the same arguments as the original function, but returns the logical negation of its result.

```php
use function Functional\not;

$isEven = fn ($number) => $number % 2 === 0;
$isOdd = not($isEven);
$isOdd(1); // true
$isOdd(2); // false
```

## Other

`mixed Functional\memoize(callable $callback[, array $arguments = []], [string|array $key = null]])`
Returns and stores the result of the function call. Second call to the same function will return the same result without calling the function again

`string value_to_key(...$values)`
Builds an array key out of any values, correctly handling object identity and traversables. Resources are not supported

# Mathematical functions

`mixed Functional\maximum(array|Traversable $collection)`
Returns the highest element in the array or collection


`mixed Functional\minimum(array|Traversable $collection)`
Returns the lowest element in the array or collection


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

# Transformation functions


## partition()
Splits a collection into two or more by callback(s). For each element,
each partition is called in turn, until one returns a truthy value, or
all have been called. Each element is placed in the partition for
first callback it passes; if no callback succeeds, it is placed in the
final partition.

``array Functional\partition(array|Traversable $collection, callable $callback ...)``

```php
use function Functional\partition;

list($admins, $guests, $users) = partition(
    $collection,
    fn ($user) => $user->isAdmin(),
    fn ($user) => $user->isGuest()
);
```


## group()
Splits a collection into groups by the index returned by the callback

``array Functional\group(array|Traversable $collection, callable $callback)``

```php
use function Functional\group;

$groupedUser = group($users, fn ($user) => $user->getGroup()->getName());
```


## zip() & zip_all()
Recombines arrays by index and applies a callback optionally

``array Functional\zip(array|Traversable $collection1[, array|Traversable ...[, callable $callback]])``

``array Functional\zip_all(array|Traversable $collection1[, array|Traversable ...[, callable $callback]])``

```php
use function Functional\zip;

zip(['one', 'two', 'three'], [1, 2, 3]); // [['one', 1], ['two', 2], ['three', 3]]

zip(
    ['one', 'two', 'three'],
    [1, 2, 3],
    fn ($one, $two) => $one . '|' . $two
); // ['one|1', 'two|2', 'three|3']
```

``zip()`` uses the keys of the first input array. ``zip_all()`` uses all the keys present in the input arrays.

## flatten()
Takes a nested combination of collections and returns their contents as a single, flat array. Does not preserve indexes.

``array Functional\flatten(array|Traversable $collection)``

```php
use function Functional\flatten;

$flattened = flatten([1, 2, 3, [1, 2, 3, 4], 5]); // [1, 2, 3, 1, 2, 3, 4, 5];
```


## reduce_left() & reduce_right()
Applies a callback to each element in the collection and reduces the collection to a single scalar value.
`Functional\reduce_left()` starts with the first element in the collection, while `Functional\reduce_right()` starts
with the last element.

``mixed Functional\reduce_left(array|Traversable $collection, callable $callback[, $initial = null])``

``mixed Functional\reduce_right(array|Traversable $collection, callable $callback[, $initial = null])``

```php
use function Functional\reduce_left;
use function Functional\reduce_right;

$str = reduce_left([2, 3], fn ($value, $index, $collection, $reduction) => $reduction . $value, 2); // '223'
$str = reduce_right([2, 3], fn ($value, $index, $collection, $reduction) => $reduction . $value, 2); // '232'
```


## intersperse()

Insert a given value between each element of a collection.

```php
use Functional\intersperse;

intersperse(['a', 'b', 'c'], '-'); // ['a', '-', 'b', '-', 'c'];
```

## Other

`array Functional\unique(array|Traversable $collection[, callback $indexer[, bool $strict = true]])`
Returns a unified array based on the index value returned by the callback, use `$strict` to change comparison mode


`array Functional\flat_map(array|Traversable $collection, callable $callback)`
Applies a callback to each element in the collection and collects the return values flattening one level of nested arrays.

# Conditional functions

## if_else()

``callable if_else(callable $if, callable $then, callable $else)``
Returns a new function that will call `$then` if the return of `$if` is truthy, otherwise calls `$else`.
All three functions will be called with the given argument.

```php
use function Functional\greater_than;
use function Functional\if_else;

$ifItIs = fn ($value) => "Yes, {$value} is greater than 1";
$ifItIsNot = fn ($value) => "Nop, {$value} isn't greater than 1";

$message = if_else(greater_than(1), $ifItIs, $ifItIsNot);

echo $message(2); // Yes, 2 is greater than 1
```

## match()

``callable match(array $conditions)``
Returns a new function that behaves like a match operator.
`$conditions` should be a bi-dimensional array with items following the given signature: `[callable $if, callable $then]`.
`$if` is the predicate, that when returns a truthy value `$then` is called.
It stops on the first match and if none of the conditions matches, `null` is returned.

```php
use function Functional\greater_than_or_equal;
use function Functional\match;

$preschool = fn ($age) => "At {$age} you go to preschool";
$primary = fn ($age) => "At {$age} you go to primary school";
$secondary = fn ($age) => "At {$age} you go to secondary school";

$stage = match([
  [greater_than_or_equal(12), $secondary],
  [greater_than_or_equal(5), $primary],
  [greater_than_or_equal(4), $preschool],
]);

echo $stage(4); // At 4 you go to preschool
echo $stage(5); // At 5 you go to primary school
echo $stage(13); // At 13 you go to secondary school
```

# Higher order comparison functions

## compare_on & compare_object_hash_on

``callable compare_on(callable $comparison; callable $keyFunction = Functional\const_function)``
Returns a compare function that can be used with e.g. `usort()`, `array_udiff`, `array_uintersect` and so on. Takes a
comparison function as the first argument, pick e.g. `strcmp`, `strnatcmp` or `strnatcasecmp`. Second argument can be a
key function that is applied to both parameters passed to the compare function.

``callable compare_object_hash_on(callable $comparison = 'strnatcasecmp', callable $keyFunction = 'Functional\const_function')``
Returns a compare function function that expects `$left` and `$right` to be an object and compares them using the value
of `spl_object_hash`. First argument is the comparison function, pick e.g. `strcmp`, `strnatcmp` or `strnatcasecmp`.
Takes a key function as an optional argument that is invoked on both parameters passed to the compare function. It is
just a shortcut to `compare_on` as it composes the given key function with `spl_object_hash()` as a key function.

# Miscellaneous

## concat()
Concatenates zero or more strings.

```php
use function Functional\concat;

$fooBar = concat('foo', 'bar'); // 'foobar'
```

## const_function()
Returns a new function that will constantly return its first argument.

```php
use function Functional\const_function;

$one = const_function(1);
$one(); // 1
```

## id()
Proxy function that does nothing except returning its first argument.

```php
use function Functional\id;

id(1); // 1
```

## tap()
``mixed tap(mixed $value, callable $callback)``

Calls the given Closure with the given value, then returns the value.

```php
use function Functional\tap;

tap(create_user('John Doe'), fn ($user) => send_welcome_email($user))->login();
```

## repeat()

Creates and returns a function that can be used to execute the given closure multiple times.

```php
use function Functional\repeat;

repeat(function () {
    echo 'foo';
})(3); // prints 'foofoofoo' to screen
``` 

## noop()

A no-operation function, i.e. a function that does nothing.

``void Functional\noop()``
