 - [Overview](00-index.md)
 - [Chapter 1: list comprehension](01-list-comprehension.md)
 - [Chapter 2: partial application](02-partial-application.md)
 - Chapter 3: access functions
 - [Chapter 4: function functions](04-function-functions.md)
 - [Chapter 5: mathematical functions](05-mathematical-functions.md)
 - [Chapter 6: transformation functions](06-transformation-functions.md)
 - [Chapter 7: miscellaneous](07-miscellaneous.md)

# Access functions

Functional PHP comes with a set of invocation helpers that ease calling function or methods or accessing nested values.


## with()
Invoke a callback on a value if the value is not null

```php
<?php
use function Functional\with;

$retval = with($value, function($value) {
    $this->doSomethingWithValue($value);

    return 'my_result';
});
```

`with()` returns whatever the callback returns. In the above example
`$retval` would be `'my_result'`.

## invoke_if()

``mixed Functional\invoke_if(mixed $object, string $methodName[, array $methodArguments, mixed $defaultValue])``  

```php
<?php
use function Functional\invoke_if;

// if $user is an object and has a public method getId() it is returned,
// otherwise default value 0 (4th argument) is used
$userId = invoke_if($user, 'getId', [], 0);
```


## invoke(), invoke_last(), invoke_first()

``array Functional\invoke(array|Traversable $collection, string $methodName[, array $methodArguments])``  
Invokes method `$methodName` on each object in the `$collection` and returns the results of the call
 
``mixed Functional\invoke_first(array|Traversable $collection, string $methodName[, array $methodArguments])``  
Invokes method `$methodName` on the first object in the `$collection` and returns the results of the call
 
``mixed Functional\invoke_last(array|Traversable $collection, string $methodName[, array $methodArguments])``  
Invokes method `$methodName` on the last object in the `$collection` and returns the results of the call


## pluck()
Fetch a single property from a collection of objects or arrays.

``array Functional\pluck(array|Traversable $collection, string|integer|float|null $propertyName)``  

```php
<?php
use function Functional\pluck;

$names = pluck($users, 'name');
```


## pick()
Pick a single element from a collection of objects or an array by index.
If no such index exists, return the default value.

``array Functional\pick(array|Traversable $collection, mixed $propertyName, mixed $default, callable $callback)``  

```php
<?php
use function Functional\pick;

$array = ['one' => 1, 'two' => 2, 'three' => 3];
pick($array, 'one'); // -> 1
pick($array, 'ten'); // -> null
pick($array, 'ten', 10); // -> 10
```


## first_index_of()
Returns the first index holding specified value in the collection. Returns false if value was not found

``array Functional\first_index_of(array|Traversable $collection, mixed $value)``  

```php
<?php
use function Functional\first_index_of;

// $index will be 0
$index = first_index_of(['value', 'value'], 'value');
```


## last_index_of()
Returns the last index holding specified value in the collection. Returns false if value was not found

``array Functional\last_index_of(array|Traversable $collection, mixed $value)``  

```php
<?php
use function Functional\last_index_of;

// $index will be 1
$index = last_index_of(['value', 'value'], 'value');
```


## indexes_of()
Returns a list of array indexes, either matching the predicate or strictly equal to the the passed value. Returns an empty array if no values were found.

``array Functional\indexes_of(Traversable|array $collection, mixed|callable $value)``  

```php
<?php
use function Functional\indexes_of;

// $indexes will be array(0, 2)
$indexes = indexes_of(['value', 'value2', 'value'], 'value');
```
