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
 * @template K
 * @template V
 * @template V2
 *
 * @param iterable<K,V> $collection
 * @param callable(V,K,iterable<K,V>,V2):V2 $callback
 * @param V2 $initial
 *
 * @return V2
 *
 * @no-named-arguments
 */
function reduce_right($collection, callable $callback, $initial = null)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    $data = [];
    foreach ($collection as $index => $value) {
        $data[] = [$index, $value];
    }

    if (0 === count($data)) {
        return $initial;
    }

    while ((list($index, $value) = \array_pop($data))) {
        $initial = $callback($value, $index, $collection, $initial);
    }

    return $initial;
}
