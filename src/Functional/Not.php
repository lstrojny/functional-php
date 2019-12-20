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
 * @template V
 * @param callable(V): bool $function The function to run value against
 * @return callable(V): bool A negation version on the given $function
 */
function not(callable $function): callable
{
    return static function ($value) use ($function): bool {
        return !$function($value);
    };
}
