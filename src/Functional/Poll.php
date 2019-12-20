<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional;

use AppendIterator;
use ArrayIterator;
use Functional\Exceptions\InvalidArgumentException;
use InfiniteIterator;
use Traversable;

/**
 * Retry a callback until it returns a truthy value or the timeout (in microseconds) is reached
 *
 * @param callable(int, int): bool $callback Callback receives retry count and delay and returns true on success
 * @param integer $timeout Timeout in microseconds
 * @param iterable<numeric> $delaySequence Default: no delay between calls
 * @throws InvalidArgumentException
 * @return mixed|false Truthy value on success, false on timeout
 */
function poll(callable $callback, int $timeout, iterable $delaySequence = null)
{
    InvalidArgumentException::assertIntegerGreaterThanOrEqual($timeout, 0, __FUNCTION__, 2);

    $retry = 0;

    $delays = new AppendIterator();
    if ($delaySequence) {
        /** @psalm-suppress ArgumentTypeCoercion */
        $delays->append(
            new InfiniteIterator(
                $delaySequence instanceof Traversable ? $delaySequence: new ArrayIterator($delaySequence)
            )
        );
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

    // Never reached because of InfiniteIterator
    return false;
}
