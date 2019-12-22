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
 * Returns an array containing the elements of the list without its last element.
 *
 * @template TKey of array-key
 * @template TValue of mixed
 * @param iterable<TKey, TValue> $collection
 * @return array<TKey, TValue>
 */
function but_last($collection)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    $butLast = \is_array($collection) ? $collection : \iterator_to_array($collection);
    \array_pop($butLast);

    return $butLast;
}
