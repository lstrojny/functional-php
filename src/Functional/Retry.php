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
 */
function retry(callable $callback, $retries, Traversable $delaySequence = null)
{
    InvalidArgumentException::assertIntegerGreaterThanOrEqual($retries, 1, __FUNCTION__, 2);

    if ($delaySequence) {
        $delays = new AppendIterator();
        $delays->append(new InfiniteIterator($delaySequence));
        $delays->append(new InfiniteIterator(new ArrayIterator([0])));
        $delays = new LimitIterator($delays, $retries);
    } else {
        $delays = array_fill_keys(range(0, $retries), 0);
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
            usleep($delay);
        }

        ++$retry;
    }
}
