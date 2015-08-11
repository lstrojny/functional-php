<?php
/**
 * Copyright (C) 2015 by Dan Revel <dan@nopolabs.com>
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
 * flat_map works applying a function (callback) that returns a sequence for each element in a collection,
 * and flattening the results into the resulting array.
 *
 * flat_map(...) differs from flatten(map(...)) because it only flattens one level of nesting,
 * whereas flatten will recursively flatten nested collections.
 *
 * For example if map(collection, callback) returns [[],1,[2,3],[[4]]]
 * then flat_map(collection, callback) will return [1,2,3,[4]]
 * while flatten(map(collection, callback)) will return [1,2,3,4]
 *
 * @param Traversable|array $collection
 * @param callable $callback
 * @return array
 */
function flat_map($collection, callable $callback)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    $flattened = [];

    foreach ($collection as $index => $element) {

        $result = $callback($element, $index, $collection);

        if (is_array($result) || $result instanceof Traversable) {

            foreach ($result as $item) {
                $flattened[] = $item;
            }

        } elseif ($result !== null) {
            $flattened[] = $result;
        }
    }

    return $flattened;
}
