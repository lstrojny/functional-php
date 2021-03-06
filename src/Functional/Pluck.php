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
use Traversable;

/**
 * Extract a property from a collection of objects.
 *
 * @param Traversable|array $collection
 * @param string $propertyName
 * @return array
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
