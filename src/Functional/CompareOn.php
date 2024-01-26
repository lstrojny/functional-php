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
 * @template V
 * @template V2
 * @template R of int
 *
 * @param callable(V,V):R $comparison A function that compares the two values. Pick e.g. strcmp() or strnatcasecmp()
 * @param null|callable(V2):V $reducer A function that takes an argument and returns the value that should be compared
 *
 * @return ($reducer is null ? callable(V,V):R : callable(V2,V2):R)
 *
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
