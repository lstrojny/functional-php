<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional;

use AppendIterator;
use ArrayIterator;
use Functional\Exceptions\InvalidArgumentException;
use InfiniteIterator;

/**
 * Retry a callback until it returns a truthy value or the timeout (in microseconds) is reached
 *
 * @template R
 *
 * @param callable(int,int):R $callback
 * @param non-negative-int $timeout Timeout in microseconds
 * @param null|\Iterator $delaySequence Default: no delay between calls
 *
 * @return false|R
 *
 * @no-named-arguments
 *@throws InvalidArgumentException
 *
 */
function poll(callable $callback, $timeout, \Iterator $delaySequence = null)
{
    InvalidArgumentException::assertIntegerGreaterThanOrEqual($timeout, 0, __FUNCTION__, 2);

    $retry = 0;

    $delays = new AppendIterator();
    if ($delaySequence) {
        $delays->append(new InfiniteIterator($delaySequence));
    }
    $delays->append(new InfiniteIterator(new ArrayIterator([0])));

    $limit = \microtime(true) + ($timeout / 100000);

    foreach ($delays as $delay) {
        $value = $callback($retry, $delay);

        if ($value) {
            return $value;
        }

        if (\microtime(true) > $limit) {
            return false;
        }

        if ($delay > 0) {
            \usleep($delay);
        }

        ++$retry;
    }

    return false;
}
