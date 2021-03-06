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
use Traversable;

/**
 * Retry a callback until the number of retries are reached or the callback does no longer throw an exception
 *
 * @param callable $callback
 * @param integer $retries
 * @param Traversable|null $delaySequence Default: no delay between calls
 * @throws Exception Any exception thrown by the callback
 * @throws InvalidArgumentException
 * @return mixed Return value of the function
 * @no-named-arguments
 */
function retry(callable $callback, $retries, Traversable $delaySequence = null)
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
}
