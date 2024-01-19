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
use Traversable;

/**
 * Sorts a collection with a user-defined function, optionally preserving array keys
 *
 * @template K of array-key
 * @template V
 *
 * @param iterable<K,V> $collection
 * @param callable(V,V,iterable<K,V>):int $callback
 * @param bool $preserveKeys
 *
 * @return ($preserveKeys is true ? array<K,V> : list<V>)
 *
 * @no-named-arguments
 */
function sort($collection, callable $callback, $preserveKeys = false)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);
    InvalidArgumentException::assertBoolean($preserveKeys, __FUNCTION__, 3);

    if ($collection instanceof Traversable) {
        $array = \iterator_to_array($collection);
    } else {
        $array = $collection;
    }

    $fn = $preserveKeys ? 'uasort' : 'usort';

    $fn($array, function ($left, $right) use ($callback, $collection) {
        return $callback($left, $right, $collection);
    });

    return $array;
}
