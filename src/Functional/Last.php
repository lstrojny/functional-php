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
 * Looks through each element in the collection, returning the last one that passes a truthy test (callback).
 * Callback arguments will be element, index, collection
 *
 * @template K of array-key
 * @template V
 *
 * @param iterable<K,V> $collection
 * @param null|callable(V,K,iterable<K,V>):bool $callback
 *
 * @return (
 *     $collection is non-empty-array
 *         ? ($callback is null ? V : null|V)
 *         : null|V
 * )
 *
 * @no-named-arguments
 */
function last($collection, callable $callback = null)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    $match = null;
    foreach ($collection as $index => $element) {
        if ($callback === null || $callback($element, $index, $collection)) {
            $match = $element;
        }
    }

    return $match;
}
