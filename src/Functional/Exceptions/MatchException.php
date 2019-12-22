<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Exceptions;

class MatchException extends InvalidArgumentException
{
    /**
     * @param array $conditions
     * @param callable-string $callee
     * @throw MatchException
     * @return void
     */
    public static function assert(array $conditions, $callee)
    {
        foreach ($conditions as $key => $condition) {
            static::assertArray($key, $condition, $callee);
            static::assertLength($key, $condition, $callee);
            static::assertCallables($key, $condition, $callee);
        }
    }

    /**
     * @param array-key $key
     * @param mixed $condition
     * @param callable-string $callee
     * @psalm-assert array $condition
     * @throw MatchException
     * @return void
     */
    private static function assertArray($key, $condition, $callee)
    {
        if (!\is_array($condition)) {
            throw new self(
                \sprintf(
                    '%s() expects condition at key %d to be array, %s given',
                    $callee,
                    $key,
                    \gettype($condition)
                )
            );
        }
    }

    /**
     * @param array-key $key
     * @param array $condition
     * @param callable-string $callee
     * @throw MatchException
     * @return void
     */
    private static function assertLength($key, $condition, $callee)
    {
        if (\count($condition) < 2) {
            throw new self(
                \sprintf(
                    '%s() expects size of condition at key %d to be greater than or equals to 2, %d given',
                    $callee,
                    $key,
                    \count($condition)
                )
            );
        }
    }

    /**
     * @param array-key $key
     * @param array $condition
     * @param callable-string $callee
     * @throw MatchException
     * @return void
     */
    private static function assertCallables($key, $condition, $callee)
    {
        if (!\is_callable($condition[0]) || !\is_callable($condition[1])) {
            throw new self(
                \sprintf(
                    '%s() expects first two items of condition at key %d to be callables',
                    $callee,
                    $key
                )
            );
        }
    }
}
