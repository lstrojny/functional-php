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
 * @psalm-type _FunctionToCurry = callable(...TArg): TReturn
 * @psalm-type _CurriedFunction = callable(...TArg): FunctionToCurry|CurriedFunction
 * @param int $count number of arguments you want to curry
 * @param FunctionToCurry $function the function you want to curry
 * @return CurriedFunction|FunctionToCurry a curryied version of the given function
 * @return callable
 */
function curry_n($count, callable $function): callable
{
    /**
     * @param list<TArg> $arguments
     * @return CurriedFunction
     */
    $accumulator = static function (array $arguments) use ($count, $function, &$accumulator): callable {
        return
            /**
             * @psalm-param TArg $newArguments
             * @psalm-return CurriedFunction|FunctionToCurry
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
