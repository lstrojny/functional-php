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
 * Produces a new array of elements by mapping each element in collection through a transformation function (callback).
 * Callback arguments will be element, index, collection
 *
 * @template K of array-key
 * @template V
 * @template V2
 *
 * @param iterable<K,V> $collection
 * @param callable(V,K,iterable<K,V>):V2 $callback
 *
 * @return ($collection is list<V> ? list<V2> : array<K,V2>)
 *
 * @no-named-arguments
 */
function map($collection, callable $callback)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    $aggregation = [];

    foreach ($collection as $index => $element) {
        $aggregation[$index] = $callback($element, $index, $collection);
    }

    return $aggregation;
}
