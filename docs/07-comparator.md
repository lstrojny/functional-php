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

## comparator & object_hash_comparator

``callable compare_on(callable $comparison; callable $reducer = Functional\const_function)`` 
Returns a comparator function that can be used with e.g. `usort()`, `array_udiff`, `array_uintersect` and so on. Takes a
comparison function as the first argument, pick e.g. `strcmp` or `strnatcasecmp`. Second argument can be a reducer that
is applied to both parameters passed to the comparator.

``callable compare_object_hash_on(callable $comparison = 'strnatcasecmp', callable $reducer = 'Functional\const_function')`` 
Returns a comparator function that expects `$left` and `$right` to be an object and compares them using the value of
`spl_object_hash`. First argument is the comparison function, pick e.g. `` or ``. Takes a reducer as an optional
argument that is invoked on both parameters passed to the comparator. It is just a shortcut to `comparator` as it
composes the given reducer with `spl_object_hash()` reducer.
