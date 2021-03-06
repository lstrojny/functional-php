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
 * Returns true if some of the elements in the collection pass the callback truthy test. Short-circuits and stops
 * traversing the collection if a truthy element is found. Callback arguments will be value, index, collection
 *
 * @param Traversable|array $collection
 * @param callable|null $callback
 * @return bool
 * @no-named-arguments
 */
function some($collection, callable $callback = null)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    if ($callback === null) {
        $callback = '\Functional\id';
    }

    foreach ($collection as $index => $element) {
        if ($callback($element, $index, $collection)) {
            return true;
        }
    }

    return false;
}
