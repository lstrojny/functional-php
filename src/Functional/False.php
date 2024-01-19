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
 * Returns true if all elements of the collection are strictly false or the collection is empty
 *
 * @param iterable<mixed> $collection
 *
 * @return bool
 *
 * @no-named-arguments
 */
function false($collection)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    foreach ($collection as $value) {
        if ($value !== false) {
            return false;
        }
    }

    return true;
}
