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
 * Return a new function that captures the return value of $callback in $result and returns the callbacks return value
 *
 * @template TArg of mixed
 * @template TResult of mixed
 * @param callable(...TArg): TResult $callback
 * @param-out TResult $result
 * @return callable(...TArg): TResult
 */
function capture(callable $callback, &$result)
{
    return
    /**
     * @param TArg $args
     * @return TResult
     */
    static function (...$args) use ($callback, &$result) {
        $result = $callback(...$args);

        return $result;
    };
}
