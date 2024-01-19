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
 * Returns an array containing the elements of the list without its last element.
 *
 * @template T
 *
 * @param iterable<T> $collection
 *
 * @return iterable<T>
 *
 * @no-named-arguments
 */
function but_last($collection)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    $butLast = \is_array($collection) ? $collection : \iterator_to_array($collection);
    \array_pop($butLast);

    return $butLast;
}
