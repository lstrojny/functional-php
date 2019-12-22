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
 * Returns an integer less than, equal to, or greater than zero when
 * $a is respectively less than, equal to, or greater than $b.
 *
 * @template V
 * @param V $b
 * @return callable(V): int
 */
function lexicographic_compare($b): callable
{
    return
    /**
     * @param V $a
     */
    static function ($a) use ($b): int {
        return $a <=> $b;
    };
}
