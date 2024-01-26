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
 * Returns a list of array indexes, either matching the predicate or strictly equal to the passed value.
 * Returns an empty array if no values were found.
 *
 * @template K of array-key
 * @template V
 *
 * @param iterable<K,V> $collection
 * @param V $value
 *
 * @return list<K>
 *
 * @no-named-arguments
 */
function indexes_of($collection, $value)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    $result = [];

    if (\is_callable($value)) {
        foreach ($collection as $index => $element) {
            if ($element === $value($element, $index, $collection)) {
                $result[] = $index;
            }
        }
    } else {
        foreach ($collection as $index => $element) {
            if ($element === $value) {
                $result[] = $index;
            }
        }
    }

    return $result;
}
