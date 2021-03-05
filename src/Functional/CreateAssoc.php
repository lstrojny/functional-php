<?php
/**
 * Copyright (C) 2019 by Jesus Franco Martinez <tezcatl@fedoraproject.org>
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
 * Return a function that applies a set of functions to build an
 * associative array from a single input (scalar, object or sequence)
 *
 * @param array $specs associative array of keys and functions that map to the target
 * @param object|null $tameme
 * @return callable
 */
function create_assoc(array $specs, $tameme = null): callable
{
    return function ($input, $optional = null) use ($specs, $tameme) {
        $mecapal = [];

        foreach ($specs as $target => $mapper) {
            $mecapal[$target] = is_unary($mapper)
              ? \call_user_func($mapper, $input)
              : \call_user_func($mapper, $input, $optional, $tameme)
            ;
        }

        return $mecapal;
    };
}

/**
 * determine if the callable passed expect only one argument
 * @param callable $callable
 * @return bool
 * @throws \ReflectionException
 */
function is_unary($callable)
{
    if (!\is_callable($callable)) {
        return false;
    }
    $reflector = new \ReflectionFunction($callable);
    return \boolval($reflector->getNumberOfParameters() === 1);
}
