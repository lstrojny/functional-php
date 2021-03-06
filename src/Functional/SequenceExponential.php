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
use Functional\Sequences\ExponentialSequence;

/**
 * Returns an infinite, traversable sequence that exponentially grows by given percentage
 *
 * @param integer $start
 * @param integer $percentage Integer between 1 and 100
 * @return ExponentialSequence
 * @throws InvalidArgumentException
 * @no-named-arguments
 */
function sequence_exponential($start, $percentage)
{
    InvalidArgumentException::assertIntegerGreaterThanOrEqual($start, 1, __METHOD__, 1);
    InvalidArgumentException::assertIntegerGreaterThanOrEqual($percentage, 1, __METHOD__, 2);
    InvalidArgumentException::assertIntegerLessThanOrEqual($percentage, 100, __METHOD__, 2);

    return new ExponentialSequence($start, $percentage);
}
