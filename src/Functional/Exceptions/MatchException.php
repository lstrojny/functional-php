<?php
/**
 * Copyright (C) 2011-2017 by Lars Strojny <lstrojny@php.net>
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

namespace Functional\Exceptions;

class MatchException extends InvalidArgumentException
{
    public static function assert(array $conditions, $callee)
    {
        foreach ($conditions as $key => $condition) {
            static::assertArray($key, $condition, $callee);
            static::assertLength($key, $condition, $callee);
            static::assertCallables($key, $condition, $callee);
        }
    }

    private static function assertArray($key, $condition, $callee)
    {
        if (!is_array($condition)) {
            throw new static(
                sprintf(
                    '%s() expects condition at key %d to be array, %s given',
                    $callee,
                    $key,
                    gettype($condition)
                )
            );
        }
    }

    private static function assertLength($key, $condition, $callee)
    {
        if (sizeof($condition) < 2) {
            throw new static(
                sprintf(
                    '%s() expects size of condition at key %d to be greater than or equals to 2, %d given',
                    $callee,
                    $key,
                    sizeof($condition)
                )
            );
        }
    }

    private static function assertCallables($key, $condition, $callee)
    {
        if (!is_callable($condition[0]) || !is_callable($condition[1])) {
            throw new static(
                sprintf(
                    '%s() expects first two items of condition at key %d to be callables',
                    $callee,
                    $key
                )
            );
        }
    }
}
