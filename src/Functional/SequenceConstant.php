<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional;

use ArrayIterator;
use Functional\Exceptions\InvalidArgumentException;
use InfiniteIterator;

/**
 * Returns an infinite, traversable sequence of constant values
 *
 * @param integer $value
 * @return iterable<int>
 */
function sequence_constant(int $value): iterable
{
    InvalidArgumentException::assertIntegerGreaterThanOrEqual($value, 0, __FUNCTION__, 1);

    return new InfiniteIterator(new ArrayIterator([$value]));
}
