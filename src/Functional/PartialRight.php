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
 * Return a new function with the arguments partially applied starting from the right
 *
 * @param callable $callback
 * @param array ...$arguments
 * @return callable
 * @no-named-arguments
 */
function partial_right(callable $callback, ...$arguments)
{
    return function (...$innerArguments) use ($callback, $arguments) {
        return $callback(...\array_merge($innerArguments, $arguments));
    };
}
