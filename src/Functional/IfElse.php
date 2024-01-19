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
 * Performs an if/else condition over a value using functions as statements
 *
 * @template V
 * @template R1
 * @template R2
 *
 * @param callable(V):mixed $if the condition function
 * @param callable(V):R1 $then function to call if condition is true
 * @param callable(V):R2 $else function to call if condition is false
 *
 * @return callable(V):(R1|R2) a callback that returns the value of the given $then or $else functions
 *
 * @no-named-arguments
 */
function if_else(callable $if, callable $then, callable $else)
{
    return function ($value) use ($if, $then, $else) {
        return $if($value) ? $then($value) : $else($value);
    };
}
