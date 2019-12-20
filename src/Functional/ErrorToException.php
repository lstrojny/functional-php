<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional;

use ErrorException;

/**
 * Takes a function and returns a new function that wraps the callback and rethrows PHP errors as exception
 *
 * @template TArg
 * @template TReturn
 * @param callable(...TArg): TReturn $callback
 * @return callable(...TArg): TReturn
 */
function error_to_exception(callable $callback): callable
{
    return
    /**
     * @param TArg $arguments
     * @throws ErrorException Throws exception if PHP error happened
     * @return TReturn
     */
    static function (...$arguments) use ($callback) {
        try {
            \set_error_handler(
                static function (int $level, string $message, string $file = '', int $line = 0): bool {
                    throw new ErrorException($message, 0, $level, $file, $line);
                }
            );

            return $callback(...$arguments);
        } finally {
            \restore_error_handler();
        }
    };
}
