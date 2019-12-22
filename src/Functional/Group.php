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
 * Groups a collection by index returned by callback.
 *
 * @template K of array-key
 * @template TGroup of array-key
 * @template V
 * @param iterable<K, V> $collection
 * @param callable(V, K, iterable<K, V>): TGroup $callback
 * @return array<TGroup, array<K, V>>
 */
function group($collection, callable $callback): array
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
