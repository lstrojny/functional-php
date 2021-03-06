<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional;

/**
 * Call the given Closure with the given value, then return the value.
 *
 * @param  mixed  $value
 * @param  callable $callback
 * @return mixed
 * @no-named-arguments
 */
function tap($value, callable $callback)
{
    $callback($value);

    return $value;
}
