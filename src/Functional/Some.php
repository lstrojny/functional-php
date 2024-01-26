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
 * Returns true if some of the elements in the collection pass the callback truthy test. Short-circuits and stops
 * traversing the collection if a truthy element is found. Callback arguments will be value, index, collection
 *
 * @template K
 * @template V
 *
 * @param iterable<K,V> $collection
 * @param callable(V,K,iterable<K,V>):mixed $callback
 *
 * @return bool
 *
 * @no-named-arguments
 */
function some($collection, callable $callback = null)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    foreach ($collection as $index => $element) {
        if (null === $callback ? id($element) : $callback($element, $index, $collection)) {
            return true;
        }
    }

    return false;
}
