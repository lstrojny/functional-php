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
class MatchException extends InvalidArgumentException
{
    public static function assert(array $conditions, $callee)
    {
        foreach ($conditions as $key => $condition) {
            static::assertArray($key, $condition, $callee);
            static::assertLength($key, $condition, $callee);
            static::assertCallables($key, $condition, $callee);
        }
    }

    private static function assertArray($key, $condition, $callee)
    {
        if (!\is_array($condition)) {
            throw new static(
                \sprintf(
                    '%s() expects condition at key %d to be array, %s given',
                    $callee,
                    $key,
                    \gettype($condition)
                )
            );
        }
    }

    private static function assertLength($key, $condition, $callee)
    {
        if (\count($condition) < 2) {
            throw new static(
                \sprintf(
                    '%s() expects size of condition at key %d to be greater than or equals to 2, %d given',
                    $callee,
                    $key,
                    \count($condition)
                )
            );
        }
    }

    private static function assertCallables($key, $condition, $callee)
    {
        if (!\is_callable($condition[0]) || !\is_callable($condition[1])) {
            throw new static(
                \sprintf(
                    '%s() expects first two items of condition at key %d to be callables',
                    $callee,
                    $key
                )
            );
        }
    }
}
