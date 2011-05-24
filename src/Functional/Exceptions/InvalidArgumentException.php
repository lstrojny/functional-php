<?php
/**
 * Copyright (C) 2011 by Lars Strojny <lstrojny@php.net>
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

class InvalidArgumentException extends \InvalidArgumentException
{
    public static function assertCallback($callback, $callee, $parameterPosition)
    {
        if (!is_callable($callback)) {
            $type = gettype($callback);
            switch (gettype($callback)) {

                case 'array':
                    $type = 'method';
                    $callback = array_values($callback);

                    $sep = '::';
                    if (is_object($callback[0])) {
                        $callback[0] = get_class($callback[0]);
                        $sep = '->';
                    }

                    $callback = join($callback, $sep);
                    break;

                case 'object':
                    $callback = get_class($callback);
                    break;

                default:
                    $type = 'function';
                    $callback = $callback;
                    break;
            }
            throw new static(
                sprintf(
                    "%s() expects parameter %d to be a valid callback, %s '%s' not found or invalid %s name",
                    $callee,
                    $parameterPosition,
                    $type,
                    $callback,
                    $type
                )
            );
        }
    }

    public static function assertCollection($collection, $callee, $parameterPosition)
    {
        if (!is_array($collection) and !$collection instanceof \Traversable) {
            throw new static(
                sprintf(
                    '%s() expects parameter %d to be array or instance of Traversable',
                    $callee,
                    $parameterPosition
                )
            );
        }
    }

    public static function assertMethodName($methodName)
    {
        if (!is_string($methodName)) {
            throw new static('Invalid method name. Expected string, got ' . gettype($methodName));
        }
    }

    public static function assertPropertyName($propertyName)
    {
        if (!is_string($propertyName)) {
            throw new static('Invalid property name. Expected string, got ' . gettype($propertyName));
        }
    }
}
