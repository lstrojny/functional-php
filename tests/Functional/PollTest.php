<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
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
        $this->poller = $this->createMock('Functional\Tests\Poller');
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

        $this->assertSame('OH HAI', poll([$this->poller, 'poll'], 2000));
    }

    public function testPollRetriesAndGivesUpAfterTimeout()
    {
        $this->poller
            ->expects($this->at(0))
            ->method('poll')
            ->with(0, 0)
            ->willReturnCallback(
                function () {
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
