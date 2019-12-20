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
 * Returns true if all elements of the collection evaluate to true
 *
 * @template V of mixed
 * @param iterable<array-key, V> $collection
 * @return bool
 */
function truthy($collection): bool
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    foreach ($collection as $value) {
        if (!$value) {
            return false;
        }
    }

    return true;
}
