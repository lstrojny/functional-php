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
 * flat_map works applying a function (callback) that returns a sequence for each element in a collection,
 * and flattening the results into the resulting array.
 *
 * flat_map(...) differs from flatten(map(...)) because it only flattens one level of nesting,
 * whereas flatten will recursively flatten nested collections.
 *
 * For example if map(collection, callback) returns [[],1,[2,3],[[4]]]
 * then flat_map(collection, callback) will return [1,2,3,[4]]
 * while flatten(map(collection, callback)) will return [1,2,3,4]
 *
 * @param Traversable|array $collection
 * @param callable $callback
 * @return array
 * @no-named-arguments
 */
function flat_map($collection, callable $callback)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    $flattened = [];

    foreach ($collection as $index => $element) {
        $result = $callback($element, $index, $collection);

        if (\is_array($result) || $result instanceof Traversable) {
            foreach ($result as $item) {
                $flattened[] = $item;
            }
        } elseif ($result !== null) {
            $flattened[] = $result;
        }
    }

    return $flattened;
}
