<?php
/**
 * Copyright (C) 2011-2015 by Lars Strojny <lstrojny@php.net>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace Functional;

/**
 * @param callable $f
 * @param callable $g
 *
 * @return \Closure
 */
function if_not_defined(callable $f, callable $g)
{
    $checkers = [];
    foreach ((new \ReflectionFunction($f))->getParameters() as $parameter) {
        $parameterClass = $parameter->getClass();
        if ($parameterClass) {
            $checker = function ($argument) use ($parameterClass) {
                // is_object() is needed to prevent potential warning.
                return is_object($argument) && $parameterClass->isInstance($argument);
            };
        } elseif ($parameter->isArray()) {
            $checker = 'is_array';
        } elseif ($parameter->isCallable()) {
            $checker = 'is_callable';
        } else {
            $checker = function ($argument) { return true; };
        }

        if ($parameter->isOptional()) {
            $checker = function ($argument) use ($checker) {
                return $argument === null || $checker($argument);
            };
        }

        /*
         * TODO Scalar type. $parameter->hasType().
         *
         * They can't be NULL (neither when optional).
         */

        /*
         * TODO Variadic parameter. $parameter->isVariadic().
         *
         * Only the last parameter can be variadic!
         */

        $checkers[] = $checker;
    }

    return function (...$args) use ($checkers, $f, $g) {
        $defined = true;
        foreach ($checkers as $index => $checker) {
            if (array_key_exists($index, $args)) {
                $defined = $checker($args[$index]);
            } else {
                $defined = false;
            }
        }

        return $defined ? $f(...$args) : $g(...$args);
    };
}
