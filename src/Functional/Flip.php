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
 * Return a version of the given function where the arguments are provided in reverse order.
 *
 * If one argument is provided, it is passed to the function without change.
 *
 * @template TArg
 * @template TReturn
 * @param callable(...TArg): TReturn $callback the function you want to flip
 * @return callable(...TArg): TReturn a flipped version of the given function
 */
function flip(callable $callback): callable
{
    return static function (...$args) use ($callback) {
        return $callback(...\array_reverse($args));
    };
}
