 - [Overview](00-index.md)
 - [Chapter 1: list comprehension](01-list-comprehension.md)
 - [Chapter 2: partial application](02-partial-application.md)
 - [Chapter 3: access functions](03-access-functions.md)
 - [Chapter 4: function functions](04-function-functions.md)
 - [Chapter 5: mathematical functions](05-mathematical-functions.md)
 - [Chapter 6: transformation functions](06-transformation-functions.md)
 - Chapter 7: comparator
 - [Chapter 8: miscellaneous](08-miscellaneous.md)

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
