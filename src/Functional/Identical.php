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
 * Returns true if $a is equal to $b, and they are of the same type.
 *
 * @template V
 * @param V $b
 * @return callable(V): bool
 */
function identical($b): callable
{
    return
    /**
     * @param V $a
     */
    static function ($a) use ($b): bool {
        return $a === $b;
    };
}
