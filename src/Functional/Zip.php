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

/**
 * Recombines arrays by index and applies a callback optionally
 *
 * @template K of array-key
 * @template V
 * @template Z
 *
 * @param iterable<K,V>|callable(V...):Z ...$args One or more callbacks
 *
 * @return array<K,Z>
 *
 * @no-named-arguments
 */
function zip(...$args)
{
    $callback = null;
    if (\is_callable(\end($args))) {
        $callback = \array_pop($args);
    }

    foreach ($args as $position => $arg) {
        InvalidArgumentException::assertCollection($arg, __FUNCTION__, $position + 1);
    }

    $result = [];
    foreach ((array) \reset($args) as $index => $value) {
        $zipped = [];

        foreach ($args as $arg) {
            $zipped[] = isset($arg[$index]) ? $arg[$index] : null;
        }

        if ($callback !== null) {
            /** @var callable $callback */
            $zipped = $callback(...$zipped);
        }

        $result[$index] = $zipped;
    }

    return $result;
}
