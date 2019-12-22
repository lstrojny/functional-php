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
 * Creates a slice of $collection with $count elements taken from the end. If the collection has less than $count
 * elements, the whole collection will be returned as an array.
 * This function will reorder and reset the integer array indices by default. This behaviour can be changed by setting
 * preserveKeys to TRUE. String keys are always preserved, regardless of this parameter.
 *
 * @template K of array-key
 * @template V
 * @param iterable<K, V> $collection
 * @param int $count
 * @return array<K, V>
 */
function take_right($collection, $count, bool $preserveKeys = false)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);
    InvalidArgumentException::assertPositiveInteger($count, __FUNCTION__, 2);

    return \array_slice(
        \is_array($collection) ? $collection : \iterator_to_array($collection),
        0 - $count,
        $count,
        $preserveKeys
    );
}
