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
 * Drop all elements from a collection after callback returns true
 *
 * @template K of array-key
 * @template V
 *
 * @param iterable<K, V> $collection
 * @param callable(V, K, iterable<K, V>):bool $callback
 *
 * @return ($collection is list<V> ? list<V> : array<K,V>)
 *
 * @no-named-arguments
 */
function drop_last($collection, callable $callback)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);
    InvalidArgumentException::assertCallback($callback, __FUNCTION__, 2);

    $result = [];

    foreach ($collection as $index => $element) {
        if (!$callback($element, $index, $collection)) {
            break;
        }

        $result[$index] = $element;
    }

    return $result;
}
