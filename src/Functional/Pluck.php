<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional;

use ArrayAccess;
use Functional\Exceptions\InvalidArgumentException;

/**
 * Extract a property from a collection of objects.
 *
 * @template K of array-key
 *
 * @param iterable<K,mixed> $collection
 * @param non-empty-string $propertyName
 *
 * @return ($collection is list<mixed> ? list<mixed> : array<K,mixed>)
 *
 * @no-named-arguments
 */
function pluck($collection, $propertyName)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);
    InvalidArgumentException::assertPropertyName($propertyName, __FUNCTION__, 2);

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
