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
 * Returns the maximum value of a collection
 *
 * @template V
 *
 * @param iterable<V> $collection
 *
 * @return null|V
 *
 * @no-named-arguments
 */
function maximum($collection)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    $max = null;

    foreach ($collection as $element) {
        if (!\is_numeric($element)) {
            continue;
        }

        if ($element > $max || $max === null) {
            $max = $element;
        }
    }

    return $max;
}
