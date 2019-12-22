<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional;

use ArrayAccess;
use Functional\Exceptions\InvalidArgumentException;

/**
 * Extract a property from a collection of objects.
 *
 * @param iterable<array-key, object|array> $collection
 * @param array-key $propertyName
 * @return array<array-key, mixed>
 * @psalm-pure
 */
function pluck($collection, $propertyName)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);
    InvalidArgumentException::assertValidArrayKey($propertyName, __FUNCTION__, 2);

    $aggregation = [];

    foreach ($collection as $index => $element) {
        $value = null;

        if (\is_object($element) && isset($element->{$propertyName})) {
            $value = $element->{$propertyName};
        } elseif ((\is_array($element) || $element instanceof ArrayAccess) && isset($element[$propertyName])) {
            $value = $element[$propertyName];
        }

        $aggregation[$index] = $value;
    }

    return $aggregation;
}
