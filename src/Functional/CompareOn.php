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
 * Returns a comparison function that can be used with e.g. `usort()`
 *
 * @param callable $comparison A function that compares the two values. Pick e.g. strcmp() or strnatcasecmp()
 * @param callable $reducer A function that takes an argument and returns the value that should be compared
 * @return callable
 * @no-named-arguments
 */
function compare_on(callable $comparison, callable $reducer = null)
{
    if ($reducer === null) {
        return static function ($left, $right) use ($comparison) {
            return $comparison($left, $right);
        };
    }

    return static function ($left, $right) use ($reducer, $comparison) {
        return $comparison($reducer($left), $reducer($right));
    };
}
