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
 * Returns the first index holding specified value in the collection. Returns false if value was not found
 *
 * @param Traversable|array $collection
 * @param mixed $value
 * @return mixed
 * @no-named-arguments
 */
function first_index_of($collection, $value)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    if (\is_callable($value)) {
        foreach ($collection as $index => $element) {
            if ($element === $value($element, $index, $collection)) {
                return $index;
            }
        }
    } else {
        foreach ($collection as $index => $element) {
            if ($element === $value) {
                return $index;
            }
        }
    }

    return false;
}
