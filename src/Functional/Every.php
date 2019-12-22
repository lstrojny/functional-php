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
 * Returns true if every value in the collection passes the callback truthy test. Opposite of Functional\none().
 * Callback arguments will be element, index, collection
 *
 * @template K of array-key
 * @template V
 * @param iterable<K, V> $collection
 * @param callable(V, K, iterable<K, V>): bool $callback
 * @return bool
 * @psalm-pure
 */
function every($collection, callable $callback = null): bool
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    if ($callback === null) {
        $callback = '\Functional\id';
    }

    foreach ($collection as $index => $element) {
        if (!$callback($element, $index, $collection)) {
            return false;
        }
    }

    return true;
}
