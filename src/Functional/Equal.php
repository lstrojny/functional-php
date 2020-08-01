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
 * Performs an equal comparison
 *
 * @param mixed $b the value to compare to
 * @return callable(mixed): bool the function to perform the comparison
 * @psalm-pure
 */
function equal($b): callable
{
    return
    /** @param mixed $a */
    static function ($a) use ($b): bool {
        return $a == $b;
    };
}
