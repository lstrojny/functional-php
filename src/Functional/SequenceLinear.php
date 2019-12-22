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
use Functional\Sequences\LinearSequence;

/**
 * Returns an infinite, traversable sequence that linearly grows by given amount
 *
 * @param int $start
 * @param int $amount
 * @return iterable<int>
 */
function sequence_linear(int $start, int $amount): iterable
{
    InvalidArgumentException::assertIntegerGreaterThanOrEqual($start, 0, __FUNCTION__, 1);

    return new LinearSequence($start, $amount);
}
