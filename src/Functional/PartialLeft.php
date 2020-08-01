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
 * Return a new function with the arguments partially applied starting from the left side
 *
 * @template TArg1
 * @template TArg2
 * @template TReturn
 * @param callable(...TArg1, ...TArg2): TReturn $callback
 * @param TArg1 $arguments
 * @return callable(...TArg2): TReturn
 * @psalm-pure
 */
function partial_left(callable $callback, ...$arguments): callable
{
    return
    /**
     * @param TArg2 $innerArguments
     */
    static function (...$innerArguments) use ($callback, $arguments) {
        /** @var TArg1 $arguments */
        return $callback(...\array_merge($arguments, $innerArguments));
    };
}
