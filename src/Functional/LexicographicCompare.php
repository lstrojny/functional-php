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
 * Returns an integer less than, equal to, or greater than zero when
 * $a is respectively less than, equal to, or greater than $b.
 *
 * @param mixed $b
 * @return \Closure(mixed)
 * @no-named-arguments
 */
function lexicographic_compare($b)
{
    return function ($a) use ($b) {
        return $a <=> $b;
    };
}
