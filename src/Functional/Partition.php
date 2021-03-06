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
 * Partitions a collection by callback predicate results. Returns an
 * array of partition arrays, one for each predicate, and one for
 * elements which don't pass any predicate. Elements are placed in the
 * partition for the first predicate they pass.
 *
 * Elements are not re-ordered and have the same index they had in the
 * original array.
 *
 * @param Traversable|array $collection
 * @param callable ...$callbacks
 * @return array
 * @no-named-arguments
 */
function partition($collection, callable ...$callbacks)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    $partition = 0;
    $partitions = \array_fill(0, \count($callbacks) + 1, []);

    foreach ($collection as $index => $element) {
        foreach ($callbacks as $partition => $callback) {
            if ($callback($element, $index, $collection)) {
                $partitions[$partition][$index] = $element;
                continue 2;
            }
        }
        ++$partition;
        $partitions[$partition][$index] = $element;
    }

    return $partitions;
}
