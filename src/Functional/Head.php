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
 * Alias for Functional\first
 *
 * @template T
 * @template I
 *
 * @param iterable<T> $collection
 * @param null|callable(T,I,iterable<T>):bool $callback
 *
 * @return ($collection is non-empty-array ? ($callback is null ? T : T|null) : T|null)
 *
 * @no-named-arguments
 */
function head($collection, callable $callback = null)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    return first($collection, $callback);
}
