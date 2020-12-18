<?php

/**
 * @package   Functional-php
 * @author    Adrian Panicek <apanicek@pixelfederation.com>
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/adrianpanicek/functional-php
 */

namespace Functional;

use Traversable;
use Functional\Exceptions\InvalidArgumentException;

/**
 * Sort collection by array keys
 *
 * @param Traversable|array $collection
 * @param callable|null $callback
 *
 * @return array
 */
function key_sort($collection, callable $callback = null)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    if ($collection instanceof Traversable) {
        $array = \iterator_to_array($collection);
    } else {
        $array = $collection;
    }

    if ($callback === null) {
        \ksort($array);

        return $array;
    }

    \uksort($array, static function ($a, $b) use ($callback, $array) {
        return $callback($a, $b, $array);
    });

    return $array;
}
