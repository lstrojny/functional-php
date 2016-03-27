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
use Functional\Exceptions\NoSuchElementException;
use Functional\Iterators\ReverseIterator;
use Traversable;

/**
 * Looks through each element in the collection, returning the last one that passes a truthy test (callback).
 * Callback arguments will be element, index, collection
 *
 * @param Traversable|array $collection
 * @param callable $callback
 * @return mixed
 * @throws NoSuchElementException if the collection is empty
 */
function last($collection, callable $callback = null)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    if (isset($callback)) {
        return find(new ReverseIterator($collection), $callback);
    }

    $last = end($collection);

    if (key($collection) === null) {
        throw new NoSuchElementException("The collection is empty");
    } else {
        return $last;
    }
}
