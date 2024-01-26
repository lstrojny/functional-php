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
 * Calls the method named by $methodName on each value in the collection.
 * Any extra arguments passed to invoke will be forwarded on to the method invocation.
 *
 * @template K of array-key
 *
 * @param iterable<K,object> $collection
 * @param non-empty-string $methodName
 * @param array<mixed> $arguments
 *
 * @return ($collection is list<object> ? list<mixed> : array<K,mixed>)
 *
 * @no-named-arguments
 */
function invoke($collection, $methodName, array $arguments = [])
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);
    InvalidArgumentException::assertMethodName($methodName, __FUNCTION__, 2);

    $aggregation = [];

    foreach ($collection as $index => $element) {
        $value = null;

        $callback = [$element, $methodName];
        if (\is_callable($callback)) {
            $value = $callback(...$arguments);
        }

        $aggregation[$index] = $value;
    }

    return $aggregation;
}
