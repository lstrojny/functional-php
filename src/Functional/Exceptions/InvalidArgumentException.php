<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Exceptions;

use ArrayAccess;
use Traversable;

class InvalidArgumentException extends \InvalidArgumentException
{
    /**
     * @param mixed $collection
     * @param callable-string $callee
     * @param int $parameterPosition
     * @psalm-assert iterable $collection
     * @throws InvalidArgumentException
     * @return void
     */
    public static function assertCollection($collection, $callee, int $parameterPosition = 0)
    {
        self::assertCollectionAlike($collection, Traversable::class, $callee, $parameterPosition);
    }

    /**
     * @param mixed $collection
     * @param callable-string $callee
     * @param int $parameterPosition
     * @psalm-assert array|ArrayAccess $collection
     * @throws InvalidArgumentException
     * @return void
     */
    public static function assertArrayAccess($collection, string $callee, int $parameterPosition = 0)
    {
        self::assertCollectionAlike($collection, ArrayAccess::class, $callee, $parameterPosition);
    }

    /**
     * @param mixed $methodName
     * @param callable-string $callee
     * @param int $parameterPosition
     * @psalm-assert string $methodName
     * @throws InvalidArgumentException
     * @return void
     */
    public static function assertMethodName($methodName, string $callee, int $parameterPosition = 0)
    {
        if (!\is_string($methodName)) {
            throw new self(
                \sprintf(
                    '%s to be string, %s given',
                    self::getErrorMessage($callee, $parameterPosition),
                    self::getType($methodName)
                )
            );
        }
    }

    /**
     * @param mixed $value
     * @param callable-string $callee
     * @param int $parameterPosition
     * @psalm-assert int $value
     * @throws InvalidArgumentException
     * @return void
     */
    public static function assertPositiveInteger($value, string $callee, int $parameterPosition = 0)
    {
        if ((string)(int)$value !== (string)$value || $value < 0) {
            $type = self::getType($value);
            $type = $type === 'integer' ? 'negative integer' : $type;

            throw new self(
                \sprintf(
                    '%s to be positive integer, %s given',
                    self::getErrorMessage($callee, $parameterPosition),
                    $type
                )
            );
        }
    }

    /**
     * @param mixed $key
     * @param callable-string $callee
     * @param int $parameterPosition
     * @psalm-assert array-key $methodName
     * @throws InvalidArgumentException
     * @return void
     */
    public static function assertValidArrayKey($key, $callee, int $parameterPosition = 0)
    {
        $keyTypes = ['NULL', 'string', 'integer', 'double', 'boolean'];

        $keyType = \gettype($key);

        if (!\in_array($keyType, $keyTypes, true)) {
            throw new self(
                \sprintf(
                    '%s to be a valid array key, "%s" given. Expected "%s" or "%s"',
                    self::getErrorMessage($callee, $parameterPosition),
                    self::getType($key),
                    \array_pop($keyTypes),
                    \implode('", "', $keyTypes)
                )
            );
        }
    }

    /**
     * @param mixed $value
     * @param integer $limit
     * @param callable-string $callee
     * @param int $parameterPosition
     * @psalm-assert int $value
     * @throws InvalidArgumentException
     * @return void
     */
    public static function assertIntegerGreaterThanOrEqual($value, $limit, string $callee, int $parameterPosition = 0)
    {
        if (!\is_int($value) || $value < $limit) {
            throw new self(
                \sprintf(
                    '%s to be an integer greater than or equal to %d',
                    self::getErrorMessage($callee, $parameterPosition),
                    $limit
                )
            );
        }
    }

    /**
     * @param mixed $value
     * @param integer $limit
     * @param callable-string $callee
     * @param integer $parameterPosition
     * @psalm-assert int $value
     * @throws InvalidArgumentException
     * @return void
     */
    public static function assertIntegerLessThanOrEqual($value, int $limit, string $callee, int $parameterPosition = 0)
    {
        if (!\is_int($value) || $value > $limit) {
            throw new self(
                \sprintf(
                    '%s to be an integer less than or equal to %d',
                    self::getErrorMessage($callee, $parameterPosition),
                    $limit
                )
            );
        }
    }

    /**
     * @param array $args
     * @param int $position
     * @throws InvalidArgumentException
     * @return void
     */
    public static function assertResolvablePlaceholder(array $args, int $position)
    {
        if (\count($args) === 0) {
            throw new self(
                \sprintf('Cannot resolve parameter placeholder at position %d. Parameter stack is empty.', $position)
            );
        }
    }

    /**
     * @param mixed $collection
     * @param class-string $className
     * @param callable-string $callee
     * @param int $parameterPosition
     * @throws InvalidArgumentException
     * @return void
     */
    private static function assertCollectionAlike($collection, $className, string $callee, int $parameterPosition = 0)
    {
        if (!\is_array($collection) && !$collection instanceof $className) {
            throw new self(
                \sprintf(
                    '%s to be array or instance of %s, %s given',
                    self::getErrorMessage($callee, $parameterPosition),
                    $className,
                    self::getType($collection)
                )
            );
        }
    }

    /**
     * @param mixed $value
     * @return class-string|string
     */
    private static function getType($value): string
    {
        return \is_object($value) ? \get_class($value) : \gettype($value);
    }

    /**
     * @param callable-string $callee
     * @param int $parameterPosition
     * @return string
     */
    private static function getErrorMessage(string $callee, int $parameterPosition): string
    {
        $message = $parameterPosition === 0
            ? '%1$s() expects return value'
            : '%1$s() expects parameter %2$d';

        return \sprintf($message, $callee, $parameterPosition);
    }

    /**
     * @param mixed $value
     * @param callable-string $callee
     */
    public static function assertNonZeroInteger($value, $callee)
    {
        if (!\is_int($value) || $value == 0) {
            throw new static(\sprintf('%s expected parameter %d to be non-zero', $callee, $value));
        }
    }
}
