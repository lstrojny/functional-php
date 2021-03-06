<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional;

/**
 * Returns a comparison function that can be used with e.g. `usort()`
 *
 * @param callable $comparison A function that compares the two values. Pick e.g. strcmp() or strnatcasecmp()
 * @param callable $keyFunction A function that takes an argument and returns the value that should be compared
 * @return callable
 * @no-named-arguments
 */
function compare_object_hash_on(callable $comparison, callable $keyFunction = null)
{
    $keyFunction = $keyFunction ? compose($keyFunction, 'spl_object_hash') : 'spl_object_hash';

    return compare_on($comparison, $keyFunction);
}
