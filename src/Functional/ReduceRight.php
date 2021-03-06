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
function reduce_right($collection, callable $callback, $initial = null)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    $data = [];
    foreach ($collection as $index => $value) {
        $data[] = [$index, $value];
    }

    while ((list($index, $value) = \array_pop($data))) {
        $initial = $callback($value, $index, $collection, $initial);
    }

    return $initial;
}
