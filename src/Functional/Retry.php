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
use Exception;
use InfiniteIterator;
use LimitIterator;

/**
 * Retry a callback until the number of retries are reached or the callback does no longer throw an exception
 *
 * @template R
 *
 * @param callable(int,int):R $callback
 * @param int<-1,max> $retries
 * @param null|\Iterator $delaySequence Default: no delay between calls
 *
 * @throws Exception Any exception thrown by the callback
 * @throws InvalidArgumentException
 * @throws \LogicException Reached the end of execution before retries number and before success (should not happen)
 *
 * @return R Return value of the function
 *
 * @no-named-arguments
 */
function retry(callable $callback, $retries, \Iterator $delaySequence = null)
{
    InvalidArgumentException::assertIntegerGreaterThanOrEqual($retries, 1, __FUNCTION__, 2);

    if ($delaySequence) {
        $delays = new AppendIterator();
        $delays->append(new InfiniteIterator($delaySequence));
        $delays->append(new InfiniteIterator(new ArrayIterator([0])));
        $delays = new LimitIterator($delays, 0, $retries);
    } else {
        $delays = \array_fill_keys(\range(0, $retries), 0);
    }

    $retry = 0;
    foreach ($delays as $delay) {
        try {
            return $callback($retry, $delay);
        } catch (Exception $e) {
            if ($retry === $retries - 1) {
                throw $e;
            }
        }

        if ($delay > 0) {
            \usleep($delay);
        }

        ++$retry;
    }

    throw new \LogicException();
}
