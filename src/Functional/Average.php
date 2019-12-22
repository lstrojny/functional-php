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
 * Returns the average of all numeric values in the array or null if no numeric value was found
 *
 * @param iterable<array-key, numeric|mixed> $collection
 * @return numeric|null
 * @psalm-pure
 */
function average($collection)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    $sum = null;
    $divisor = 0;

    foreach ($collection as $element) {
        if (\is_numeric($element)) {
            $sum = ($sum === null) ? $element : $sum + $element;
            ++$divisor;
        }
    }

    if ($sum === null) {
        return null;
    }

    return $sum / $divisor;
}
