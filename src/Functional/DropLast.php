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
 * Drop all elements from a collection after callback returns true
 *
 * @template K of array-key
 * @template V
 * @param iterable<K, V> $collection
 * @param callable(V, K, iterable<K, V>): bool $callback
 * @return array<K, V>
 * @psalm-pure
 */
function drop_last($collection, callable $callback): array
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    $result = [];

    foreach ($collection as $index => $element) {
        if (!$callback($element, $index, $collection)) {
            break;
        }

        $result[$index] = $element;
    }

    return $result;
}
