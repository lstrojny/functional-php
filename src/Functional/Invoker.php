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
 * Returns a function that invokes method `$method` with arguments `$methodArguments` on the object
 *
 * @param string $methodName
 * @param array $arguments
 * @return callable
 * @no-named-arguments
 */
function invoker($methodName, array $arguments = [])
{
    InvalidArgumentException::assertMethodName($methodName, __FUNCTION__, 1);

    return static function ($object) use ($methodName, $arguments) {
        return $object->{$methodName}(...$arguments);
    };
}
