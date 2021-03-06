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
 * Takes a collection and returns the product of all elements
 *
 * @param Traversable|array $collection
 * @param integer|float $initial
 * @return integer|float
 * @no-named-arguments
 */
function product($collection, $initial = 1)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    $result = $initial;
    foreach ($collection as $value) {
        if (\is_numeric($value)) {
            $result *= $value;
        }
    }

    return $result;
}
