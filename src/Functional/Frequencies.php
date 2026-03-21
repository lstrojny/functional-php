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
 * Returns a new array mapping each distinct value to the number of times it appears in the collection.
 * An optional callback can be provided to determine the key to count by.
 *
 * @param Traversable|array $collection
 * @param callable|null $callback
 * @return array
 * @no-named-arguments
 */
function frequencies($collection, ?callable $callback = null): array
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    $frequencies = [];

    foreach ($collection as $index => $element) {
        if ($callback) {
            $key = $callback($element, $index, $collection);
        } else {
            $key = $element;
        }

        InvalidArgumentException::assertValidArrayKey($key, __FUNCTION__);

        if (\is_numeric($key)) {
            $key = (int) $key;
        }

        if (!isset($frequencies[$key])) {
            $frequencies[$key] = 0;
        }

        $frequencies[$key]++;
    }

    return $frequencies;
}
