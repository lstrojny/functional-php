<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional;

use Functional\Exceptions\InvalidArgumentException;
use Traversable;

/**
 * Returns a list of array indexes, either matching the predicate or strictly equal to the the passed value. Returns an
 * empty array if no values were found.
 *
 * @template K of array-key
 * @template V
 * @param iterable<K, V> $collection
 * @param V|callable(V, K, iterable<K, V>): bool $value
 * @return list<K>
 * @psalm-pure
 */
function indexes_of($collection, $value): array
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
