<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional;

use Functional\Exceptions\InvalidArgumentException;
use Traversable;

/**
 * @param Traversable|array $collection
 * @param callable $callback
 * @param mixed $initial
 * @return mixed
 * @no-named-arguments
 */
function reduce_left($collection, callable $callback, $initial = null)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    foreach ($collection as $index => $value) {
        $initial = $callback($value, $index, $collection, $initial);
    }

    return $initial;
}
