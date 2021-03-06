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
 * Returns all items from $collection except first element (head). Preserves $collection keys.
 * Takes an optional callback for filtering the collection.
 *
 * @param Traversable|array $collection
 * @param callable $callback
 * @return array
 * @no-named-arguments
 */
function tail($collection, callable $callback = null)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    $tail = [];
    $isHead = true;

    foreach ($collection as $index => $element) {
        if ($isHead) {
            $isHead = false;
            continue;
        }

        if (!$callback || $callback($element, $index, $collection)) {
            $tail[$index] = $element;
        }
    }

    return $tail;
}
