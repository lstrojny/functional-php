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
 * Accepts a converging function and a list of branching functions and returns a new function.
 *
 * The results of each branching function are passed as arguments
 * to the converging function to produce the return value.
 *
 * @param callable $convergingFunction Will be invoked with the return values of all branching functions as its arguments
 * @param callable[] $branchingFunctions A list of functions
 * @return callable A flipped version of the given function
 * @no-named-arguments
 */
function converge($convergingFunction, array $branchingFunctions)
{
    return function (...$values) use ($convergingFunction, $branchingFunctions) {
        $result = [];

        foreach ($branchingFunctions as $branchingFunction) {
            $result[] = $branchingFunction(...$values);
        }

        return $convergingFunction(...$result);
    };
}
