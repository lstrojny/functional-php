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
 * Return a new function that captures the return value of $callback in $result and returns the callbacks return value
 *
 * @param callable $callback
 * @param mixed $result
 * @return callable
 * @no-named-arguments
 */
function capture(callable $callback, &$result)
{
    return function (...$args) use ($callback, &$result) {
        $result = $callback(...$args);

        return $result;
    };
}
