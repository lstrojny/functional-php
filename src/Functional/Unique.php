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
 * Returns an array of unique elements
 *
 * @template K of array-key
 * @template V
 * @template I
 * @param iterable<K, V> $collection
 * @param callable(V, K, iterable<K, V>): I $callback
 * @param bool $strict
 * @return array<K, V>
 */
function unique($collection, callable $callback = null, bool $strict = true): array
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    $indexes = [];
    $aggregation = [];
    foreach ($collection as $key => $element) {
        if ($callback) {
            $index = $callback($element, $key, $collection);
        } else {
            $index = $element;
        }

        if (!\in_array($index, $indexes, $strict)) {
            $aggregation[$key] = $element;

            $indexes[] = $index;
        }
    }

    return $aggregation;
}
