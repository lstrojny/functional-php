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
namespace Functional\Tests;

use ArrayIterator;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Exception;
use function Functional\retry;

interface Retryer
{
    public function retry();
}

class RetryTest extends AbstractTestCase
{
    /** @var MockObject */
    private $retryer;

    public function setUp()
    {
        parent::setUp();
        $this->retryer = $this->getMock('Functional\Tests\Retryer');
    }

    public function testTriedOnceIfItSucceeds()
    {
        $this->retryer
            ->expects($this->once())
            ->method('retry')
            ->with(0, 0)
            ->willReturn('value');

        $this->assertSame('value', retry([$this->retryer, 'retry'], 10));
    }

    public function testRetriedIfItFails()
    {
        $this->retryer
            ->expects($this->at(0))
            ->method('retry')
            ->with(0, 0)
            ->willThrowException(new Exception());
        $this->retryer
            ->expects($this->at(1))
            ->method('retry')
            ->with(1, 0)
            ->willReturn('value');

        $this->assertSame('value', retry([$this->retryer, 'retry'], 10));
    }

    public function testThrowsExceptionIfRetryCountIsReached()
    {
        $this->retryer
            ->expects($this->at(0))
            ->method('retry')
            ->with(0, 0)
            ->willThrowException(new Exception('first'));
        $this->retryer
            ->expects($this->at(1))
            ->method('retry')
            ->with(1, 0)
            ->willThrowException(new Exception('second'));

        $this->setExpectedException('Exception', 'second');
        retry([$this->retryer, 'retry'], 2);
    }

    public function testRetryWithEmptyDelaySequence()
    {
        $this->retryer
            ->expects($this->at(0))
            ->method('retry')
            ->with(0, 0)
            ->willThrowException(new Exception('first'));
        $this->retryer
            ->expects($this->at(1))
            ->method('retry')
            ->with(1, 0)
            ->willThrowException(new Exception('second'));

        $this->setExpectedException('Exception', 'second');
        retry([$this->retryer, 'retry'], 2, new ArrayIterator([]));
    }

    public function testThrowsExceptionIfRetryCountNotAtLeast1()
    {
        $this->expectArgumentError(
            'Functional\retry() expects parameter 2 to be an integer greater than or equal to 1'
        );
        retry([$this->retryer, 'retry'], 0);
    }

    public function testUsesDelayTraversableForSleeping()
    {
        $this->retryer
            ->expects($this->at(0))
            ->method('retry')
            ->with(0, 0)
            ->willThrowException(new Exception('first'));
        $this->retryer
            ->expects($this->at(1))
            ->method('retry')
            ->with(1, 0)
            ->willThrowException(new Exception('second'));

        $this->setExpectedException('Exception', 'second');
        retry([$this->retryer, 'retry'], 2);
    }

    public function testDelayerSmallerThanRetries()
    {
        $this->retryer
            ->expects($this->at(0))
            ->method('retry')
            ->with(0, 10)
            ->willThrowException(new Exception('first'));
        $this->retryer
            ->expects($this->at(1))
            ->method('retry')
            ->with(1, 20)
            ->willThrowException(new Exception('second'));
        $this->retryer
            ->expects($this->at(2))
            ->method('retry')
            ->with(2, 10)
            ->willThrowException(new Exception('third'));
        $this->retryer
            ->expects($this->at(3))
            ->method('retry')
            ->with(3, 20)
            ->willThrowException(new Exception('four'));

        $this->setExpectedException('Exception', 'four');
        retry([$this->retryer, 'retry'], 4, new ArrayIterator([1 => 10, 2 => 20]));
    }
}
