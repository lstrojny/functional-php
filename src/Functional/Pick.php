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

use ArrayAccess;
use Functional\Exceptions\InvalidArgumentException;

/**
 * Pick a single element from a collection of objects or arrays by index.
 * If no such index exists, return the default value.
 *
 * @param ArrayAccess|array $collection
 * @param mixed $index
 * @param mixed $default
 * @param callable $callback Custom function to check if index exists, default function is "isset"
 * @return mixed
 */
function pick($collection, $index, $default = null, callable $callback = null)
{
    InvalidArgumentException::assertArrayAccess($collection, __FUNCTION__, 1);

    if ($callback === null) {
        if (!isset($collection[$index])) {
            return $default;
        }
    } else {
        if (!$callback($collection, $index)) {
            return $default;
        }
    }

    return $collection[$index];
}
