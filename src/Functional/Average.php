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
 * Returns the average of all numeric values in the array or null if no numeric value was found
 *
 * @param iterable<mixed> $collection
 *
 * @return ($collection is iterable<int> ? float|int : (
 *     $collection is iterable<float> ? float : (
 *         $collection is iterable<int|float|numeric-string> ? float|int : null
 *     )
 * ))
 *
 * @no-named-arguments
 */
function average($collection)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    $sum = null;
    $divisor = 0;

    foreach ($collection as $element) {
        if (\is_numeric($element)) {
            $sum += $element;
            ++$divisor;
        }
    }

    if ($sum === null) {
        return null;
    }

    return $sum / $divisor;
}
