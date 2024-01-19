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
 * Groups a collection by index returned by callback.
 *
 * @template K of array-key
 * @template V
 * @template G of array-key
 *
 * @param iterable<K,V> $collection
 * @param callable(V,K,iterable<K,V>):G $callback
 *
 * @return (G is numeric-string ? array<int,array<K,V>> : array<G,array<K,V>>)
 *
 * @no-named-arguments
 */
function group($collection, callable $callback)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    $groups = [];

    foreach ($collection as $index => $element) {
        $groupKey = $callback($element, $index, $collection);

        InvalidArgumentException::assertValidArrayKey($groupKey, __FUNCTION__);

        if (!isset($groups[$groupKey])) {
            $groups[$groupKey] = [];
        }

        $groups[$groupKey][$index] = $element;
    }

    return $groups;
}
