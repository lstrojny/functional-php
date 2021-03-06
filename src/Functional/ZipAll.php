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
 * Recombines arrays by index (column) and applies a callback optionally
 *
 * When the input collections are different lengths the resulting collections
 * will all have the length which is required to fit all the keys
 *
 * @param array|Traversable ...$args One or more callbacks
 * @return array
 * @no-named-arguments
 */
function zip_all(...$args)
{
    /** @var callable|null $callback */
    $callback = null;
    if (\is_callable(\end($args))) {
        $callback = \array_pop($args);
    }

    foreach ($args as $position => $arg) {
        InvalidArgumentException::assertCollection($arg, __FUNCTION__, $position + 1);
    }

    $resultKeys = [];
    foreach ($args as $arg) {
        foreach ($arg as $index => $value) {
            $resultKeys[] = $index;
        }
    }

    $resultKeys = \array_unique($resultKeys);

    $result = [];

    foreach ($resultKeys as $key) {
        $zipped = [];

        foreach ($args as $arg) {
            $zipped[] = isset($arg[$key]) ? $arg[$key] : null;
        }

        $result[$key] = $zipped;
    }

    if ($callback !== null) {
        foreach ($result as $key => $column) {
            $result[$key] = $callback(...$column);
        }
    }

    return $result;
}
