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
 * Return a value if the given condition is true.
 *
 * @param mixed|\Closure $condition
 * @param mixed|\Closure $value
 * @param mixed|\Closure $default
 *
 * @return mixed
 * @no-named-arguments
 */
function when($condition, $value, $default = null)
{
    $condition = $condition instanceof \Closure ? $condition() : $condition;

    if ($condition) {
        return $value instanceof \Closure ? $value($condition) : $value;
    }

    return $default instanceof \Closure ? $default($condition) : $default;
}
