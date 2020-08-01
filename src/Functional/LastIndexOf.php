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
 * Returns the last index holding specified value in the collection. Returns false if value was not found
 *
 * @template K of array-key
 * @template V
 * @param iterable<K, V> $collection
 * @param V|callable(V, K, iterable<K, V>): bool $value
 * @return K|false
 * @psalm-pure
 */
function last_index_of($collection, $value)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    $matchingIndex = false;

    if (\is_callable($value)) {
        foreach ($collection as $index => $element) {
            if ($element === $value($element, $index, $collection)) {
                $matchingIndex = $index;
            }
        }
    } else {
        foreach ($collection as $index => $element) {
            if ($element === $value) {
                $matchingIndex = $index;
            }
        }
    }

    return $matchingIndex;
}
