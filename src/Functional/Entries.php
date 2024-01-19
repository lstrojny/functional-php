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
 * Inspired by JavaScript’s `Object.entries`, and Python’s `enumerate`,
 * convert a key-value map into an array of key-value pairs
 *
 * @see \Functional\from_entries
 *
 * @template K
 * @template V
 *
 * @param iterable<K,V> $collection
 * @param int $start
 *
 * @return array<int, array{K,V}>
 *
 * @no-named-arguments
 */
function entries($collection, int $start = 0)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    $aggregation = [];
    foreach ($collection as $key => $value) {
        $aggregation[$start++] = [$key, $value];
    }

    return $aggregation;
}
