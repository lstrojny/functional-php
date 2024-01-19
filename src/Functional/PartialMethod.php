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
 * Returns a function that expects an object as the first param and tries to invoke the given method on it
 *
 * @template V
 *
 * @param non-empty-string $methodName
 * @param array<V> $arguments
 * @param mixed $defaultValue
 *
 * @return callable(object):mixed
 *
 * @no-named-arguments
 */
function partial_method($methodName, array $arguments = [], $defaultValue = null)
{
    InvalidArgumentException::assertMethodName($methodName, __FUNCTION__, 1);

    return function ($object) use ($methodName, $arguments, $defaultValue) {
        if (!\is_callable([$object, $methodName])) {
            return $defaultValue;
        }
        return $object->{$methodName}(...$arguments);
    };
}
