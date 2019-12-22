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
 * Takes a function and returns a new function that wraps the callback and suppresses the PHP error
 *
 * @template TArg
 * @template TReturn
 * @param callable(...TArg): TReturn $callback
 * @return callable(...TArg): TReturn
 */
function suppress_error(callable $callback): callable
{
    return
    /**
     * @param TArg $arguments
     * @return TReturn
     */
    static function (...$arguments) use ($callback) {
        try {
            \set_error_handler(static function () {
            });

            return $callback(...$arguments);
        } finally {
            \restore_error_handler();
        }
    };
}
