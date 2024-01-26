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
 * Returns true if all elements of the collection evaluate to true
 *
 * @param iterable<mixed> $collection
 *
 * @return bool
 *
 * @no-named-arguments
 */
function truthy($collection)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    foreach ($collection as $value) {
        if (!$value) {
            return false;
        }
    }

    return true;
}
