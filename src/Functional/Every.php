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
 * Returns true if every value in the collection passes the callback truthy test. Opposite of Functional\none().
 * Callback arguments will be element, index, collection
 *
 * @template K
 * @template V
 *
 * @param iterable<K,V> $collection
 * @param null|callable(V,K,iterable<K,V>):bool $callback
 *
 * @return bool
 *
 * @no-named-arguments
 */
function every($collection, callable $callback = null)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    foreach ($collection as $index => $element) {
        if (!(null === $callback ? id($element) : $callback($element, $index, $collection))) {
            return false;
        }
    }

    return true;
}
