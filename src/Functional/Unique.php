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
 * Returns an array of unique elements
 *
 * @param Traversable|array $collection
 * @param callable $callback
 * @param bool $strict
 * @return array
 * @no-named-arguments
 */
function unique($collection, callable $callback = null, $strict = true)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    $indexes = [];
    $aggregation = [];
    foreach ($collection as $key => $element) {
        if ($callback) {
            $index = $callback($element, $key, $collection);
        } else {
            $index = $element;
        }

        if (!\in_array($index, $indexes, $strict)) {
            $aggregation[$key] = $element;

            $indexes[] = $index;
        }
    }

    return $aggregation;
}
