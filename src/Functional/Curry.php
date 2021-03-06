<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional;

use ReflectionMethod;
use ReflectionFunction;
use Closure;

/**
 * Return a curryied version of the given function. You can decide if you also
 * want to curry optional parameters or not.
 *
 * @param callable $function the function to curry
 * @param bool $required curry optional parameters ?
 * @return callable a curryied version of the given function
 * @no-named-arguments
 */
function curry(callable $function, $required = true)
{
    if (\method_exists('Closure', 'fromCallable')) {
        // Closure::fromCallable was introduced in PHP 7.1
        $reflection = new ReflectionFunction(Closure::fromCallable($function));
    } else {
        if (\is_string($function) && \strpos($function, '::', 1) !== false) {
            $reflection = new ReflectionMethod($function);
        } elseif (\is_array($function) && \count($function) === 2) {
            $reflection = new ReflectionMethod($function[0], $function[1]);
        } elseif (\is_object($function) && \method_exists($function, '__invoke')) {
            $reflection = new ReflectionMethod($function, '__invoke');
        } else {
            $reflection = new ReflectionFunction($function);
        }
    }

    $count = $required ?
        $reflection->getNumberOfRequiredParameters() :
        $reflection->getNumberOfParameters();

    return curry_n($count, $function);
}
