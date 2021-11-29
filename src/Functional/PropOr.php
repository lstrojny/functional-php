<?php
/**
 * Copyright (C) 2019 by Sergei Kolesnikov <win0err@gmail.com>
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
 * Returns the indicated property of the item, if it exists, or the default value, if not.
 *
 * @param mixed $default Default value if the property not found
 * @param mixed $key The property name
 * @param array|object $item The object or array to query
 * @return mixed The value
 */
function prop_or($default, $key, $item)
{
    switch (true) {
        case \is_array($item) || $item instanceof \ArrayAccess:
            return $item[$key] ?? $default;

        case \is_object($item) && isset($item->$key):
            return $item->$key ?? $default;

        default:
            return $default;
    }
}
