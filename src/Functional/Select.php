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
 * Looks through each element in the list, returning an array of all the elements that pass a truthy test (callback).
 * Opposite is Functional\reject(). Callback arguments will be element, index, collection
 *
 * @template K of array-key
 * @template V
 * @param iterable<K, V> $collection
 * @param callable(V, K, iterable<K, V>): bool $callback
 * @return array<K, V>
 */
function select($collection, callable $callback = null): array
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    $aggregation = [];

    if ($callback === null) {
        $callback = '\Functional\id';
    }

    foreach ($collection as $index => $element) {
        if ($callback($element, $index, $collection)) {
            $aggregation[$index] = $element;
        }
    }

    return $aggregation;
}
