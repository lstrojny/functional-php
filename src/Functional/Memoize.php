<?php
/**
 * Copyright (C) 2011 - 2012 by Lars Strojny <lstrojny@php.net>
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
 * Memoizes callbacks and returns there value instead of calling them
 *
 * @param callable $callback Callable closure or function
 * @param array $callbackArguments Arguments
 * @param array|string $memoizeKey Optional memoize key to override the auto calculated hash
 * @return mixed
 */
function memoize($callback, array $callbackArguments = array(), $memoizeKey = null)
{
    Exceptions\InvalidArgumentException::assertCallback($callback, __FUNCTION__, 0);

    static $generateKey = null,
           $storage = array();

    if (!$generateKey) {
        $generateKey = function($value) use (&$generateKey) {
            $type = gettype($value);
            if ($type == 'array') {
                $key = join(':', map($value, $generateKey));
            } elseif ($type === 'object') {
                $key = get_class($value) . ':' . spl_object_hash($value);
            } else {
                $key = (string) $value;
            }

            return $key;
        };
    }

    if ($memoizeKey === null) {
        $key = $generateKey(array_merge(array($callback), $callbackArguments));
    } else {
        $key = $generateKey($memoizeKey);
    }

    if (!isset($storage[$key]) && !array_key_exists($key, $storage)) {
        $storage[$key] = call_user_func_array($callback, $callbackArguments);
    }

    return $storage[$key];
}
