 - [Overview](00-index.md)
 - [Chapter 1: list comprehension](01-list-comprehension.md)
 - [Chapter 2: partial application](02-partial-application.md)
 - [Chapter 3: access functions](03-access-functions.md)
 - [Chapter 4: function functions](04-function-functions.md)
 - [Chapter 5: mathematical functions](05-mathematical-functions.md)
 - [Chapter 6: transformation functions](06-transformation-functions.md)
 - Chapter 7: miscellaneous

# Miscellaneous


## const_function()
Returns new function, that will constantly return its first argument (constant function)

```php
<?php
use function Functional\const_function;

$one = const_function(1);
$one() // -> 1
```


## id()
Proxy function, that do nothing, except returning its first argument

```php
<?php
use function Functional\id;

1 === id(1);
```
