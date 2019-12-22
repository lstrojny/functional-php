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
 * Returns a comparison function that can be used with e.g. `usort()`
 *
 * @template TCompVal of scalar
 * @template TVal
 * @param callable(TCompVal, TCompVal): int $comparison A function that compares the two values. Pick e.g. `strcmp()` or `strnatcasecmp()`
 * @param callable(TVal): TCompVal $reducer A function that takes an argument and returns the value that should be compared
 * @return callable(TVal, TVal): int
 * @fixme file psalm issue for "Argument 1 expects empty, … provided" (when not passing a reducer)
 * @psalm-pure
 */
function compare_on(callable $comparison, callable $reducer = null): callable
{
    if ($reducer === null) {
        return
        /**
         * @param TVal $left
         * @param TVal $right
         */
        static function ($left, $right) use ($comparison): int {
            /**
             * @var TCompVal $left
             * @var TCompVal $right
             */
            return $comparison($left, $right);
        };
    }

    return
    /**
     * @param TVal $left
     * @param TVal $right
     */
    static function ($left, $right) use ($reducer, $comparison): int {
        return $comparison($reducer($left), $reducer($right));
    };
}
