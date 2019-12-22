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
 * Performs an if/else condition over a value using functions as statements
 *
 * @template V
 * @template TReturn
 * @param callable(V): bool $if   the condition function
 * @param callable(V): TReturn $then function to call if condition is true
 * @param callable(V): TReturn $else function to call if condition is false
 * @return callable(V): TReturn the return value of the given $then or $else functions
 */
function if_else(callable $if, callable $then, callable $else): callable
{
    return
    /**
     * @param V $value
     */
    static function ($value) use ($if, $then, $else) {
        return $if($value) ? $then($value) : $else($value);
    };
}
