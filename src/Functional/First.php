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

/**
 * Looks through each element in the collection, returning the first one that passes a truthy test (callback). The
 * function returns as soon as it finds an acceptable element, and doesn't traverse the entire collection. Callback
 * arguments will be element, index, collection
 *
 * @template T
 * @template I
 *
 * @param iterable<T> $collection
 * @param null|callable(T,I,iterable<T>):bool $callback
 *
 * @return  ($collection is non-empty-array ? ($callback is null ? T : T|null) : T|null)
 *
 * @no-named-arguments
 */
function first($collection, callable $callback = null)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    foreach ($collection as $index => $element) {
        if ($callback === null) {
            return $element;
        }

        if ($callback($element, $index, $collection)) {
            return $element;
        }
    }

    return null;
}
