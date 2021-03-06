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
use Traversable;

/**
 * Returns true if the collection contains the given value. If the third parameter is
 * true values will be compared in strict mode
 *
 * @param Traversable|array $collection
 * @param mixed $value
 * @param bool $strict
 * @return bool
 * @no-named-arguments
 */
function contains($collection, $value, $strict = true)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    foreach ($collection as $element) {
        if ($value === $element || (!$strict && $value == $element)) {
            return true;
        }
    }

    return false;
}
