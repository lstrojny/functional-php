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
use function Functional\poll;

interface Poller
{
    public function poll();
}

class PollTest extends AbstractTestCase
{
    /** @var MockObject */
    private $poller;

    public function setUp()
    {
        parent::setUp();
        $this->poller = $this->getMock('Functional\Tests\Poller');
    }

    public function testPollReturnsTrue()
    {
        $this->poller
            ->expects($this->once())
            ->method('poll')
            ->with(0, 0)
            ->willReturn(true);

        $this->assertTrue(poll([$this->poller, 'poll'], 1000));
    }

    public function testPollRetriesIfNotTruthy()
    {
        $this->poller
            ->expects($this->at(0))
            ->method('poll')
            ->with(0, 0)
            ->willReturn(false);
        $this->poller
            ->expects($this->at(1))
            ->method('poll')
            ->with(1, 0)
            ->willReturn('OH HAI');

        $this->assertSame('OH HAI', poll([$this->poller, 'poll'], 1000));
    }

    public function testPollRetriesAndGivesUpAfterTimeout()
    {
        $this->poller
            ->expects($this->at(0))
            ->method('poll')
            ->with(0, 0)
            ->willReturnCallback(
                function() {
                    usleep(100);
                    return false;
                }
            );

        $this->assertFalse(poll([$this->poller, 'poll'], 100));
    }

    public function testWithEmptyDelayCallsAtLeastOnce()
    {
        $this->poller
            ->expects($this->at(0))
            ->method('poll')
            ->with(0, 0)
            ->willReturn(true);

        $this->assertTrue(poll([$this->poller, 'poll'], 0, new ArrayIterator([])));
    }

    public function testThrowsExceptionIfTimeoutCountNotAtLeast0()
    {
        $this->expectArgumentError(
            'Functional\poll() expects parameter 2 to be an integer greater than or equal to 0'
        );
        poll([$this->poller, 'poll'], -1);
    }
}
