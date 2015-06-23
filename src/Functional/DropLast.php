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

use Functional\Exceptions\InvalidArgumentException;
use Traversable;

/**
 * Drop all elements from a collection after callback returns true
 *
 * @param Traversable|array $collection
 * @param callable|integer $callback
 * @return array
 */
function drop_last($collection, callable $callback)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);
    InvalidArgumentException::assertCallback($callback, __FUNCTION__, 2);

    $result = [];

    $drop = false;
    foreach ($collection as $index => $element) {

        if (!$drop && !$callback($element, $index, $collection)) {
            break;
        }

        $result[$index] = $element;
    }

    return $result;
}
