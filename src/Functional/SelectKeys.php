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
 * Select the specified keys from the array
 *
 * @template K of array-key
 * @template V
 * @param iterable<K, V> $collection
 * @param list<K> $keys
 * @return array<K, V>
 */
function select_keys($collection, array $keys): array
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    if ($collection instanceof Traversable) {
        $array = \iterator_to_array($collection);
    } else {
        $array = $collection;
    }

    /** @var array<K, V> $list */
    $list = \array_intersect_key($array, \array_flip($keys));

    return $list;
}
