<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional;

use Traversable;
use Functional\Exceptions\InvalidArgumentException;

/**
 * Calls the method named by $methodName on last object in the collection containing a callable method named
 * $methodName. Any extra arguments passed to invoke will be forwarded on to the method invocation.
 *
 * @param Traversable|array $collection
 * @param string $methodName
 * @param array $arguments
 * @return mixed
 * @no-named-arguments
 */
function invoke_last($collection, $methodName, array $arguments = [])
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);
    InvalidArgumentException::assertMethodName($methodName, __FUNCTION__, 2);

    $lastCallback = null;

    foreach ($collection as $element) {
        $callback = [$element, $methodName];
        if (\is_callable($callback)) {
            $lastCallback = $callback;
        }
    }

    if (!$lastCallback) {
        return null;
    }

    return $lastCallback(...$arguments);
}
