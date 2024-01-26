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
 * Select the specified keys from the array
 *
 * @template K of array-key
 * @template V
 *
 * @param iterable<K,V> $collection
 * @param array<K> $keys
 *
 * @return array<K,V>
 *
 * @no-named-arguments
 */
function select_keys($collection, array $keys)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    if ($collection instanceof Traversable) {
        $array = \iterator_to_array($collection);
    } else {
        $array = $collection;
    }

    return \array_intersect_key($array, \array_flip($keys));
}
