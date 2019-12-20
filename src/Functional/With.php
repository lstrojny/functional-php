<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional;

use Functional\Exceptions\InvalidArgumentException;

/**
 * Invoke a callback on a value if the value is not null
 *
 * @psalm-pure
 *
 * @template I as null|mixed
 * @template D
 * @template R
 *
 * @psalm-param I $value
 * @psalm-param callable(I):R $callback
 * @psalm-param bool $invokeValue
 * @psalm-param null|D $default
 * @psalm-return R|D|null
 *
 * @param mixed $value
 * @param callable $callback
 * @param bool $invokeValue Set to false to not invoke $value if it is a callable. Will be removed in 2.0
 * @param mixed $default The default value to return if $value is null
 * @return mixed
 */
function with($value, callable $callback, $invokeValue = true, $default = null)
{
    InvalidArgumentException::assertCallback($callback, __FUNCTION__, 2);

    if ($value === null) {
        return $default;
    }

    if ($invokeValue && \is_callable($value)) {
        /** @psalm-suppress ImpureFunctionCall */
        \trigger_error('Invoking the value is deprecated and will be removed in 2.0', E_USER_DEPRECATED);

        $value = $value();
    }

    return $callback($value);
}
