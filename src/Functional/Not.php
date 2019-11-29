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
 * Logical negation of the given $function
 *
 * @param callable $function The function to run value against
 * @return callable A negation version on the given $function
 */
function not(callable $function)
{
    return function ($value) use ($function) {
        return !$function($value);
    };
}
