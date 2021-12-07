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
 * Return a new function that composes all functions in $functions into a single callable
 *
 * @param callable ...$functions
 * @return callable
 * @no-named-arguments
 */
function compose(callable ...$functions): callable
{
    return \array_reduce(
        $functions,
        static function ($carry, $item) {
            return static function ($x) use ($carry, $item) {
                return $item($carry($x));
            };
        },
        'Functional\id'
    );
}
