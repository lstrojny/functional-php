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
 * Returns the minimum value of a collection
 *
 * @param Traversable|array $collection
 * @return integer|float
 * @no-named-arguments
 */
function minimum($collection)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    $min = null;

    foreach ($collection as $index => $element) {
        if (!\is_numeric($element)) {
            continue;
        }

        if ($element < $min || $min === null) {
            $min = $element;
        }
    }

    return $min;
}
