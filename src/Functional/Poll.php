<?php
/**
 * Copyright (C) 2011-2015 by Lars Strojny <lstrojny@php.net>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
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
 * @param callable $callback
 * @param integer $timeout Timeout in microseconds
 * @param Traversable|null $delaySequence Default: no delay between calls
 * @throws InvalidArgumentException
 * @return boolean
 */
function poll(callable $callback, $timeout, Traversable $delaySequence = null)
{
    InvalidArgumentException::assertIntegerGreaterThanOrEqual($timeout, 0, __FUNCTION__, 2);

    $retry = 0;

    $delays = new AppendIterator();
    if ($delaySequence) {
        $delays->append(new InfiniteIterator($delaySequence));
    }
    $delays->append(new InfiniteIterator(new ArrayIterator([0])));

    $limit = microtime(true) + ($timeout / 100000);

    foreach ($delays as $delay) {
        $value = $callback($retry, $delay);

        if ($value) {
            return $value;
        }

        if (microtime(true) > $limit) {
            return false;
        }

        if ($delay > 0) {
            usleep($delay);
        }

        ++$retry;
    }
}
