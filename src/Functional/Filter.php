<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional;

use Functional\Exceptions\InvalidArgumentException;
use Traversable;

/**
 * Alias of Functional\select()
 *
 * @param Traversable|array $collection
 * @param callable $callback
 * @return array
 */
function filter($collection, callable $callback)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    return select($collection, $callback);
}
