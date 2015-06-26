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
namespace Functional\Exceptions;

class InvalidArgumentException extends \InvalidArgumentException
{
    /**
     * @param callable $callback
     * @param string $callee
     * @param integer $parameterPosition
     * @throws InvalidArgumentException
     */
    public static function assertCallback($callback, $callee, $parameterPosition)
    {
        if (!is_callable($callback)) {

            if (!is_array($callback) && !is_string($callback)) {
                throw new static(
                    sprintf(
                        '%s() expected parameter %d to be a valid callback, no array, string, closure or functor given',
                        $callee,
                        $parameterPosition
                    )
                );
            }

            $type = gettype($callback);
            switch ($type) {

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

                default:
                    $type = 'function';
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
        self::assertCollectionAlike($collection, 'Traversable', $callee, $parameterPosition);
    }

    public static function assertArrayAccess($collection, $callee, $parameterPosition)
    {
        self::assertCollectionAlike($collection, 'ArrayAccess', $callee, $parameterPosition);
    }

    public static function assertMethodName($methodName, $callee, $parameterPosition)
    {
        if (!is_string($methodName)) {
            throw new static(
                sprintf(
                    '%s() expects parameter %d to be string, %s given',
                    $callee,
                    $parameterPosition,
                    gettype($methodName)
                )
            );
        }
    }

    /**
     * @param string $propertyName
     * @param string $callee
     * @param integer $parameterPosition
     * @throws InvalidArgumentException
     */
    public static function assertPropertyName($propertyName, $callee, $parameterPosition)
    {
        if (!is_string($propertyName) &&
            !is_integer($propertyName) &&
            !is_float($propertyName) &&
            !is_null($propertyName)) {
            throw new static(
                sprintf(
                    '%s() expects parameter %d to be a valid property name or array index, %s given',
                    $callee,
                    $parameterPosition,
                    gettype($propertyName)
                )
            );
        }
    }

    public static function assertPositiveInteger($value, $callee, $parameterPosition)
    {
        if ((string)(int)$value !== (string)$value || $value < 0) {

            $type = gettype($value);
            $type = $type === 'integer' ? 'negative integer' : $type;

            throw new static(
                sprintf(
                    '%s() expects parameter %d to be positive integer, %s given',
                    $callee,
                    $parameterPosition,
                    $type
                )
            );
        }
    }

    /**
     * @param mixed $key
     * @param string $callee
     * @throws static
     */
    public static function assertValidArrayKey($key, $callee)
    {
        $keyTypes = ['NULL', 'string', 'integer', 'double', 'boolean'];

        $keyType = gettype($key);

        if (!in_array($keyType, $keyTypes, true)) {
            throw new static(
                sprintf(
                    '%s(): callback returned invalid array key of type "%s". Expected %4$s or %3$s',
                    $callee,
                    $keyType,
                    array_pop($keyTypes),
                    join(', ', $keyTypes)
                )
            );
        }
    }

    public static function assertArrayKeyExists($collection, $key, $callee)
    {
    	if (!isset($collection[$key])) {
            throw new static(
                sprintf(
                    '%s(): unknown key "%s"',
                    $callee,
                    $key
                )
            );
    	}
    }

    /**
     * @param boolean $value
     * @param string $callee
     * @param integer $parameterPosition
     * @throws InvalidArgumentException
     */
    public static function assertBoolean($value, $callee, $parameterPosition)
    {
        if (!is_bool($value)) {
            throw new static(
                sprintf(
                    '%s() expects parameter %d to be boolean',
                    $callee,
                    $parameterPosition
                )
            );
        }
    }

    /**
     * @param boolean $value
     * @param string $callee
     * @param integer $parameterPosition
     * @throws InvalidArgumentException
     */
    public static function assertInteger($value, $callee, $parameterPosition)
    {
        if (!is_integer($value)) {
            throw new static(
                sprintf(
                    '%s() expects parameter %d to be integer',
                    $callee,
                    $parameterPosition
                )
            );
        }
    }

    /**
     * @param integer $value
     * @param integer $limit
     * @param string $callee
     * @param integer $parameterPosition
     * @throws InvalidArgumentException
     */
    public static function assertIntegerGreaterThanOrEqual($value, $limit, $callee, $parameterPosition)
    {
        if (!is_integer($value) || $value < $limit) {
            throw new static(
                sprintf(
                    '%s() expects parameter %d to be an integer greater than or equal to %d',
                    $callee,
                    $parameterPosition,
                    $limit
                )
            );
        }
    }

    /**
     * @param integer $value
     * @param integer $limit
     * @param string $callee
     * @param integer $parameterPosition
     * @throws InvalidArgumentException
     */
    public static function assertIntegerLessThanOrEqual($value, $limit, $callee, $parameterPosition)
    {
        if (!is_integer($value) || $value > $limit) {
            throw new static(
                sprintf(
                    '%s() expects parameter %d to be an integer less than or equal to %d',
                    $callee,
                    $parameterPosition,
                    $limit
                )
            );
        }
    }

    public static function assertResolvablePlaceholder(array $args, $position)
    {
        if (count($args) === 0) {
            throw new static(
                sprintf('Cannot resolve parameter placeholder at position %d. Parameter stack is empty.', $position)
            );
        }
    }

    /**
     * @param $collection
     * @param string $className
     * @param string $callee
     * @param integer $parameterPosition
     * @throws InvalidArgumentException
     */
    private static function assertCollectionAlike($collection, $className, $callee, $parameterPosition)
    {
        if (!is_array($collection) && !$collection instanceof $className) {
            throw new static(
                sprintf(
                    '%s() expects parameter %d to be array or instance of %s',
                    $callee,
                    $parameterPosition,
                    $className
                )
            );
        }
    }
}
