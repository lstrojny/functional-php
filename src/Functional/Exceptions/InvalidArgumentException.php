<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Exceptions;

/** @internal */
class InvalidArgumentException extends \InvalidArgumentException
{
    /**
     * @param mixed $callback
     * @param string $callee
     * @param integer $parameterPosition
     * @throws InvalidArgumentException
     */
    public static function assertCallback($callback, $callee, $parameterPosition)
    {
        if (!\is_callable($callback)) {
            if (!\is_array($callback) && !\is_string($callback)) {
                throw new static(
                    \sprintf(
                        '%s() expected parameter %d to be a valid callback, no array, string, closure or functor given',
                        $callee,
                        $parameterPosition
                    )
                );
            }

            $type = \gettype($callback);
            switch ($type) {
                case 'array':
                    $type = 'method';
                    $callback = \array_values($callback);

                    $sep = '::';
                    if (\is_object($callback[0])) {
                        $callback[0] = \get_class($callback[0]);
                        $sep = '->';
                    }

                    $callback = \implode($sep, $callback);
                    break;

                default:
                    $type = 'function';
                    break;
            }

            throw new static(
                \sprintf(
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
        if (!\is_string($methodName)) {
            throw new static(
                \sprintf(
                    '%s() expects parameter %d to be string, %s given',
                    $callee,
                    $parameterPosition,
                    self::getType($methodName)
                )
            );
        }
    }

    /**
     * @param mixed $propertyName
     * @param string $callee
     * @param integer $parameterPosition
     * @throws InvalidArgumentException
     */
    public static function assertPropertyName($propertyName, $callee, $parameterPosition)
    {
        if (
            !\is_string($propertyName) &&
            !\is_int($propertyName) &&
            !\is_float($propertyName) &&
            !\is_null($propertyName)
        ) {
            throw new static(
                \sprintf(
                    '%s() expects parameter %d to be a valid property name or array index, %s given',
                    $callee,
                    $parameterPosition,
                    self::getType($propertyName)
                )
            );
        }
    }

    public static function assertPositiveInteger($value, $callee, $parameterPosition)
    {
        if ((string)(int)$value !== (string)$value || $value < 0) {
            $type = self::getType($value);
            $type = $type === 'integer' ? 'negative integer' : $type;

            throw new static(
                \sprintf(
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

        $keyType = \gettype($key);

        if (!\in_array($keyType, $keyTypes, true)) {
            throw new static(
                \sprintf(
                    '%s(): callback returned invalid array key of type "%s". Expected %4$s or %3$s',
                    $callee,
                    $keyType,
                    \array_pop($keyTypes),
                    \implode(', ', $keyTypes)
                )
            );
        }
    }

    public static function assertArrayKeyExists($collection, $key, $callee)
    {
        if (!isset($collection[$key])) {
            throw new static(
                \sprintf(
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
        if (!\is_bool($value)) {
            throw new static(
                \sprintf(
                    '%s() expects parameter %d to be boolean, %s given',
                    $callee,
                    $parameterPosition,
                    self::getType($value)
                )
            );
        }
    }

    /**
     * @param mixed $value
     * @param string $callee
     * @param integer $parameterPosition
     * @throws InvalidArgumentException
     */
    public static function assertInteger($value, $callee, $parameterPosition)
    {
        if (!\is_int($value)) {
            throw new static(
                \sprintf(
                    '%s() expects parameter %d to be integer, %s given',
                    $callee,
                    $parameterPosition,
                    self::getType($value)
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
        if (!\is_int($value) || $value < $limit) {
            throw new static(
                \sprintf(
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
        if (!\is_int($value) || $value > $limit) {
            throw new static(
                \sprintf(
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
        if (\count($args) === 0) {
            throw new static(
                \sprintf('Cannot resolve parameter placeholder at position %d. Parameter stack is empty.', $position)
            );
        }
    }

    /**
     * @param mixed $collection
     * @param string $className
     * @param string $callee
     * @param integer $parameterPosition
     * @throws InvalidArgumentException
     */
    private static function assertCollectionAlike($collection, $className, $callee, $parameterPosition)
    {
        if (!\is_array($collection) && !$collection instanceof $className) {
            throw new static(
                \sprintf(
                    '%s() expects parameter %d to be array or instance of %s, %s given',
                    $callee,
                    $parameterPosition,
                    $className,
                    self::getType($collection)
                )
            );
        }
    }

    public static function assertNonZeroInteger($value, $callee)
    {
        if (!\is_int($value) || $value == 0) {
            throw new static(\sprintf('%s expected parameter %d to be non-zero', $callee, $value));
        }
    }

    private static function getType($value)
    {
        return \is_object($value) ? \get_class($value) : \gettype($value);
    }
}
