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
 * @todo Add callable typehint when HHVM supports use of typehints with variadic arguments
 * @see https://github.com/facebook/hhvm/issues/6954
 * @no-named-arguments
 */
function compose(...$functions)
{
    return \array_reduce(
        $functions,
        function ($carry, $item) {
            return function ($x) use ($carry, $item) {
                return $item($carry($x));
            };
        },
        'Functional\id'
    );
}
