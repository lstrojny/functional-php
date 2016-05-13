 - [Overview](00-index.md)
 - [Chapter 1: list comprehension](01-list-comprehension.md)
 - [Chapter 2: partial application](02-partial-application.md)
 - [Chapter 3: access functions](03-access-functions.md)
 - Chapter 4: function functions
 - [Chapter 5: mathematical functions](05-mathematical-functions.md)
 - [Chapter 6: transformation functions](06-transformation-functions.md)
 - [Chapter 7: comparator](07-comparator.md)
 - [Chapter 8: miscellaneous](08-miscellaneous.md)

# Function functions

Function functions take a function or functions and return a new, modified version of the function.


## retry()
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


## poll()
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

## capture()
Return a new function that captures the return value of $callback in $result and returns the callbacks return value

```php
use function Functional\capture;

$fn = capture(
    function() {
        return 'Hello world';
    },
    $result
);

$fn();

var_dump($result); // 'Hello world'
```


## compose()
Return a new function that composes multiple functions into a single callable

```php
use function Functional\compose;

$plus2 = function ($x) { return $x + 2; };
$times4 = function ($x) { return $x * 4; };

$composed = compose($plus2, $times4);

$result = array_map($composed, array(1, 2, 5, 8));

var_dump($result); // array(12, 16, 28, 40)
```



## Other

`mixed Functional\memoize(callable $callback[, array $arguments = []], [mixed $key = null]])`  
Returns and stores the result of the function call. Second call to the same function will return the same result without calling the function again
