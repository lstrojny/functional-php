<?php
/**
 * Copyright (C) 2011-2017 by Gilles Crettenand <gilles@crettenand.info>
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
 */
function curry(callable $function, $required = true)
{
    if (method_exists('Closure','fromCallable')) {
        $reflection = new ReflectionFunction(Closure::fromCallable($function));
    } else {
        if (is_string($function) && strpos($function, '::', 1) !== false) {
            $reflection = new ReflectionMethod($function);
        } elseif (is_array($function) && count($function) === 2) {
            $reflection = new ReflectionMethod($function[0], $function[1]);
        } elseif (is_object($function) && method_exists($function, '__invoke')) {
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
