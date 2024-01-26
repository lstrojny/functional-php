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
 * Alias of Functional\select()
 *
 * @template K of array-key
 * @template V
 *
 * @param iterable<K,V> $collection
 * @param callable(V,K,iterable<K,V>):mixed $callback
 *
 * @return array<K,V>
 *
 * @no-named-arguments
 */
function filter($collection, callable $callback)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    return select($collection, $callback);
}
