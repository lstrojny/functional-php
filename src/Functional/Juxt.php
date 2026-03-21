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
 * Takes a list of functions and returns a new function that applies each
 * function to the given arguments and returns an array of the results.
 *
 * @param callable ...$functions
 * @return callable
 * @no-named-arguments
 */
function juxt(callable ...$functions)
{
    return function (...$values) use ($functions) {
        $result = [];

        foreach ($functions as $function) {
            $result[] = $function(...$values);
        }

        return $result;
    };
}
