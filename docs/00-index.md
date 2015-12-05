 - Overview
 - [Chapter 1: list comprehension](01-list-comprehension.md)
 - [Chapter 2: partial application](02-partial-application.md)
 - [Chapter 3: access functions](03-access-functions.md)
 - [Chapter 4: function functions](04-function-functions.md)
 - [Chapter 5: mathematical functions](05-mathematical-functions.md)
 - [Chapter 6: transformation functions](06-transformation-functions.md)
 - [Chapter 7: miscellaneous](07-miscellaneous.md)

# Functional PHP

### Import functions

Whenever you want to work with Functional PHP and not reference the fully qualified name, add `use Functional as F;` on 
top of your PHP file or use `use function Functional\function_name`. The latter is used in the documentation is the 
preferred way starting with PHP 5.6.

### Example

```php
use function Functional\map;

map(range(0, 100), function($v) {return $v + 1;});
```
