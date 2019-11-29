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
 * Returns the elements in list without the elements that the truthy test (callback) passes. The opposite of
 * Functional\select(). Callback arguments will be element, index, collection
 *
 * @param Traversable|array $collection
 * @param callable $callback
 * @return array
 */
function reject($collection, callable $callback)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    $aggregation = [];

    foreach ($collection as $index => $element) {
        if (!$callback($element, $index, $collection)) {
            $aggregation[$index] = $element;
        }
    }

    return $aggregation;
}
