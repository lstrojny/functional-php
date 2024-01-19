<?php

/**
 * @package   Functional-php
 * @author    Hugo Sales <hugo@hsal.es>
 * @copyright 2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional;

use Functional\Exceptions\InvalidArgumentException;

/**
 * Inspired by JavaScript’s `Object.fromEntries`,
 * convert an array of key-value pairs into a key-value map
 *
 * @see \Functional\entries
 *
 * @template K of array-key
 * @template V
 * @template P of array{K,V}
 *
 * @param iterable<P> $collection
 *
 * @return (K is numeric-string ? array<int,V> : array<K,V>)
 *
 * @no-named-arguments
 */
function from_entries($collection)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    $aggregation = [];
    foreach ($collection as $entry) {
        InvalidArgumentException::assertPair($entry, __FUNCTION__, 1);
        [$key, $value] = $entry;
        InvalidArgumentException::assertValidArrayKey($key, __FUNCTION__);
        $aggregation[$key] = $value;
    }

    return $aggregation;
}
