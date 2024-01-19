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
 * @template V
 * @template R of int
 *
 * @param callable(string,string):R $comparison A function that compares the two values. Pick e.g. strcmp() or strnatcasecmp()
 * @param null|callable(V):string $keyFunction A function that takes an argument and returns the value that should be compared
 *
 * @return callable(object,object):R
 *
 * @no-named-arguments
 */
function compare_object_hash_on(callable $comparison, callable $keyFunction = null)
{
    $keyFunction = $keyFunction ? compose($keyFunction, 'spl_object_hash') : 'spl_object_hash';

    return compare_on($comparison, $keyFunction);
}
