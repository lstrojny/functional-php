<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use ArrayIterator;
use PHPUnit\Framework\MockObject\MockObject;

use function Functional\poll;

interface Poller
{
    public function poll();
}

class PollTest extends AbstractTestCase
{
    /** @var MockObject */
    private $poller;

    public function setUp(): void
    {
        parent::setUp();
        $this->poller = $this->createMock(Poller::class);
    }

    public function testPollReturnsTrue(): void
    {
        $this->poller
            ->expects(self::once())
            ->method('poll')
            ->with(0, 0)
            ->willReturn(true);

        self::assertTrue(poll([$this->poller, 'poll'], 1000));
    }

    public function testPollRetriesIfNotTruthy(): void
    {
        $this->poller
            ->method('poll')
            ->withConsecutive([0, 0], [1, 0])
            ->willReturnOnConsecutiveCalls(false, 'OH HAI');

        self::assertSame('OH HAI', poll([$this->poller, 'poll'], 2000));
    }

    public function testPollRetriesAndGivesUpAfterTimeout(): void
    {
        $this->poller
            ->method('poll')
            ->withConsecutive([0, 0])
            ->willReturnCallback(
                function () {
                    \usleep(100);
                    return false;
                }
            );

        self::assertFalse(poll([$this->poller, 'poll'], 100));
    }

    public function testWithEmptyDelayCallsAtLeastOnce(): void
    {
        $this->poller
            ->method('poll')
            ->withConsecutive([0, 0])
            ->willReturn(true);

        self::assertTrue(poll([$this->poller, 'poll'], 0, new ArrayIterator([])));
    }

    public function testThrowsExceptionIfTimeoutCountNotAtLeast0(): void
    {
        $this->expectArgumentError(
            'Functional\poll() expects parameter 2 to be an integer greater than or equal to 0'
        );
        poll([$this->poller, 'poll'], -1);
    }
}
