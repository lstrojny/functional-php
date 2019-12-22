<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional;

/**
 * Return a new function that composes all functions in $functions into a single callable
 *
 * @param callable ...$functions
 * @return callable
 */
function compose(callable ...$functions): callable
{
    return \array_reduce(
        $functions,
        /**
         * @param mixed $carry
         * @param mixed $item
         */
        static function ($carry, $item) {
            return
            /**
             * @param mixed $x
             * @return mixed
             */
            static function ($x) use ($carry, $item) {
                return $item($carry($x));
            };
        },
        'Functional\id'
    );
}
