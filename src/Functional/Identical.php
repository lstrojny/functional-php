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
 * Returns true if $a is equal to $b, and they are of the same type.
 *
 * @param mixed $b
 * @return callable
 * @no-named-arguments
 */
function identical($b)
{
    return function ($a) use ($b) {
        return $a === $b;
    };
}
