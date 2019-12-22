<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional;

use Closure;
use Functional\Exceptions\InvalidArgumentException;

/**
 * Creates a function that can be used to repeat the execution of $callback.
 *
 * @param callable(): void $callback
 * @return callable(int): void
 */
function repeat(callable $callback): callable
{
    return
    /**
     * @param int $times
     * @return void
     */
    static function (int $times) use ($callback) {
        InvalidArgumentException::assertPositiveInteger($times, __FUNCTION__, 1);

        for ($i = 0; $i < $times; $i++) {
            $callback();
        }
    };
}
