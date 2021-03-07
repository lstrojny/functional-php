<?php

/**
 * @package   Functional-php
 * @author    Hugo Sales <hugo@fc.up.pt>
 * @copyright 2020 Hugo Sales
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional;

use Functional\Exceptions\InvalidArgumentException;
use Traversable;

/**
 * Call $func with only abs($count) arguments, taken either from the
 * left or right depending on the sign
 * @no-named-arguments
 */
function ary(callable $func, int $count): callable
{
    InvalidArgumentException::assertNonZeroInteger($count, __FUNCTION__);

    return function (...$args) use ($func, $count) {
        if ($count > 0) {
            return $func(...take_left($args, $count));
        } else if ($count < 0) {
            return $func(...take_right($args, -$count));
        }
    };
}
