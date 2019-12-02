<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
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
 */
function tap($value, callable $callback)
{
    $callback($value);

    return $value;
}
