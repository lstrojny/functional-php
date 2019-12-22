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
 * Return a version of the given function where the $count first arguments are curryied.
 *
 * No check is made to verify that the given argument count is either too low or too high.
 * If you give a smaller number you will have an error when calling the given function. If
 * you give a higher number, arguments will simply be ignored.
 *
 * @template TArg
 * @template TReturn
 * @param int $count number of arguments you want to curry
 * @param callable(...TArg): TReturn $function the function you want to curry
 * @return callable(...TArg): callable a curryied version of the given function
 * @return callable
 * @psalm-pure
 */
function curry_n($count, callable $function): callable
{
    /**
     * @param list<TArg> $arguments
     * @return callable(...TArg): callable
     */
    $accumulator = static function (array $arguments) use ($count, $function, &$accumulator): callable {
        return
            /**
             * @psalm-param TArg $newArguments
             * @psalm-return callable(...TArg): callable
             */
        static function (...$newArguments) use ($count, $function, $arguments, $accumulator) {
            $arguments = \array_merge($arguments, $newArguments);

            if ($count <= \count($arguments)) {
                return $function(...$arguments);
            }

            return $accumulator($arguments);
        };
    };

    return $accumulator([]);
}
