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
 * Insert a given value between each element of a collection.
 * Indices are not preserved.
 *
 * @template V
 * @param iterable<array-key, V> $collection
 * @param V $glue
 * @return list<V>
 * @psalm-pure
 */
function intersperse($collection, $glue)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    $aggregation = [];

    foreach ($collection as $element) {
        $aggregation[] = $element;
        $aggregation[] = $glue;
    }

    \array_pop($aggregation);

    return $aggregation;
}
