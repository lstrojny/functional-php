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
 * Returns a comparison function that can be used with e.g. `usort()`
 *
 * @template TCompVal of scalar
 * @template TObjIn extends object
 * @template TObjOut extends object
 * @param callable(TCompVal, TCompVal): int $comparison A function that compares the two values. Pick e.g. `strcmp()` or `strnatcasecmp()`
 * @param callable(TObjIn): TObjOut $keyFunction A function that takes an argument and returns the value that should be compared
 * @return callable(TObjIn, TObjIn): int
 */
function compare_object_hash_on(callable $comparison, callable $keyFunction = null)
{
    /** @var callable(TObjIn): scalar $keyFunction */
    $keyFunction = $keyFunction ? compose($keyFunction, 'spl_object_hash') : 'spl_object_hash';

    /**
     * @fixme report issue with psalm
     * @var callable(scalar, scalar): int $comparison
     */

    return compare_on($comparison, $keyFunction);
}
