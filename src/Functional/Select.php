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
 * Looks through each element in the list, returning an array of all the elements that pass a truthy test (callback).
 * Opposite is Functional\reject(). Callback arguments will be element, index, collection
 *
 * @template K of array-key
 * @template V
 *
 * @param iterable<K,V> $collection
 * @param callable(V,K,iterable<K,V>):mixed $callback
 *
 * @return array<K,V>
 *
 * @no-named-arguments
 */
function select($collection, callable $callback = null)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    $aggregation = [];

    foreach ($collection as $index => $element) {
        if (null === $callback ? id($element) : $callback($element, $index, $collection)) {
            $aggregation[$index] = $element;
        }
    }

    return $aggregation;
}
