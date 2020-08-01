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

/**
 * Alias for Functional\first
 *
 * @template K of array-key
 * @template V
 * @param iterable<K, V> $collection
 * @param callable(V, K, iterable<K, V>): bool $callback
 * @return V|null
 * @psalm-pure
 */
function head($collection, callable $callback = null)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    /**
     * @psalm-suppress InvalidScalarArgument
     * @fixme report bug
     */
    return first($collection, $callback);
}
