<?php
/**
 * Copyright (C) 2019, 2020 by Jesus Franco Martinez <tezcatl@fedoraproject.org>
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
 * Provides a callable that applies the functions passed as arguments from left
 * to right, first function is able to admit several arguments at once
 * @link https://github.com/lstrojny/functional-php/issues/141
 * @param callable[] ...$functions functions to be composed
 * @return callable
 */
function pipe(...$functions): callable
{
    return function () use ($functions) {
        $funArgs = \func_get_args();
        $entryFunction = \array_shift($functions);
        return \array_reduce(
            $functions,
            function ($prev, $currentFun) {
                return \call_user_func($currentFun, $prev);
            },
            \call_user_func_array($entryFunction, $funArgs)
        );
    };
}
