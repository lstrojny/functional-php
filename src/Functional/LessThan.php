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
 * Returns true if $a is strictly less than $b.
 *
 * @param mixed $b
 * @return \Closure(mixed)
 */
function less_than($b)
{
    return function ($a) use ($b) {
        return $a < $b;
    };
}
