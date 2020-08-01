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

/**
 * Returns the first index holding specified value in the collection. Returns false if value was not found
 *
 * @template K of array-key
 * @template V
 * @param iterable<K, V> $collection
 * @param V|callable(V, K, iterable<K, V>): bool $value
 * @return K|false
 * @psalm-pure
 */
function first_index_of($collection, $value)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    if (\is_callable($value)) {
        foreach ($collection as $index => $element) {
            if ($element === $value($element, $index, $collection)) {
                return $index;
            }
        }
    } else {
        foreach ($collection as $index => $element) {
            if ($element === $value) {
                return $index;
            }
        }
    }

    return false;
}
