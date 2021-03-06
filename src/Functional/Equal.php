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
 * Performs an equal comparison
 *
 * @param mixed $b the value to compare to
 *
 * @return callable the function to perform the comparison
 * @no-named-arguments
 */
function equal($b)
{
    return function ($a) use ($b) {
        return $a == $b;
    };
}
