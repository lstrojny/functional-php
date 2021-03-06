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
 * Takes a nested combination of collections and returns their contents as a single, flat array.
 * Does not preserve indexes.
 *
 * @param Traversable|array $collection
 * @return array
 * @no-named-arguments
 */
function flatten($collection)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    $stack = [$collection];
    $result = [];

    while (!empty($stack)) {
        $item = \array_shift($stack);

        if (\is_array($item) || $item instanceof Traversable) {
            foreach ($item as $element) {
                \array_unshift($stack, $element);
            }
        } else {
            \array_unshift($result, $item);
        }
    }

    return $result;
}
