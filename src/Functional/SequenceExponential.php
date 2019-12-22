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
use Functional\Sequences\ExponentialSequence;

/**
 * Returns an infinite, traversable sequence that exponentially grows by given percentage
 *
 * @param int $start
 * @param int $percentage Integer between 1 and 100
 * @return iterable<int>
 * @throws InvalidArgumentException
 * @psalm-pure
 */
function sequence_exponential(int $start, int $percentage): iterable
{
    InvalidArgumentException::assertIntegerGreaterThanOrEqual($start, 1, __METHOD__, 1);
    InvalidArgumentException::assertIntegerGreaterThanOrEqual($percentage, 1, __METHOD__, 2);
    InvalidArgumentException::assertIntegerLessThanOrEqual($percentage, 100, __METHOD__, 2);

    return new ExponentialSequence($start, $percentage);
}
