<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional;

/**
 * Calls the method named by $methodName on $object. Any extra arguments passed to invoke_if will be
 * forwarded on to the method invocation. If $method is not callable on $object, $defaultValue is returned.
 *
 * @param mixed $object
 * @param string $methodName
 * @param array $methodArguments
 * @param mixed $defaultValue
 * @return mixed
 * @no-named-arguments
 */
function invoke_if($object, $methodName, array $methodArguments = [], $defaultValue = null)
{
    $callback = [$object, $methodName];
    if (\is_callable($callback)) {
        return $callback(...$methodArguments);
    }

    return $defaultValue;
}
