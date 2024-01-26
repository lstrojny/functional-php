<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional;

use Functional\Exceptions\InvalidArgumentException;

/**
 * Invoke a callback on a value if the value is not null
 *
 * @template V
 * @template V2
 * @template V3
 *
 * @param V|callable():V|null $value
 * @param callable(V):V2 $callback
 * @param bool $invokeValue
 * @param V3 $default The default value to return if $value is null
 *
 * @return ($value is null ? V3 : V2)
 *
 * @no-named-arguments
 */
function with($value, callable $callback, $invokeValue = true, $default = null)
{
    InvalidArgumentException::assertCallback($callback, __FUNCTION__, 2);

    if ($value === null) {
        return $default;
    }

    if ($invokeValue && \is_callable($value)) {
        \trigger_error('Invoking the value is deprecated and will be removed in 2.0', E_USER_DEPRECATED);

        $value = $value();
    }

    return $callback($value);
}
