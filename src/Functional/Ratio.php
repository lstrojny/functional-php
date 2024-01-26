<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional;

use Functional\Exceptions\InvalidArgumentException;

/**
 * Takes a collection and returns the quotient of all elements
 *
 * @template V of int|float
 * @template I of int|float
 *
 * @param iterable<V> $collection
 * @param I $initial
 *
 * @return (V is int ? ($initial is int ? int|float : float) : float)
 *
 * @no-named-arguments
 */
function ratio($collection, $initial = 1)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    $result = $initial;
    foreach ($collection as $value) {
        if (\is_numeric($value)) {
            $result /= $value;
        }
    }

    return $result;
}
