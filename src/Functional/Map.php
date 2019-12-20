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
 * Produces a new array of elements by mapping each element in collection through a transformation function (callback).
 * Callback arguments will be element, index, collection
 *
 * @template K of array-key
 * @template V
 * @template TMapped
 * @param iterable<K, V> $collection
 * @param callable(V, K, iterable<K, V>): TMapped $callback
 * @return array<K, TMapped>
 */
function map($collection, callable $callback): array
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    $aggregation = [];

    foreach ($collection as $index => $element) {
        $aggregation[$index] = $callback($element, $index, $collection);
    }

    return $aggregation;
}
