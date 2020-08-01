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
 * Returns true if all elements of the collection are strictly true
 *
 * @param iterable<array-key, bool> $collection
 * @return bool
 * @psalm-pure
 */
function true($collection): bool
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    foreach ($collection as $value) {
        if ($value !== true) {
            return false;
        }
    }

    return true;
}
