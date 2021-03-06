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
use Exception;
use PHPUnit\Framework\MockObject\MockObject;

use function Functional\retry;

interface Retryer
{
    public function retry();
}

class RetryTest extends AbstractTestCase
{
    /** @var MockObject */
    private $retryer;

    public function setUp(): void
    {
        parent::setUp();
        $this->retryer = $this->createMock(Retryer::class);
    }

    public function testTriedOnceIfItSucceeds(): void
    {
        $this->retryer
            ->expects(self::once())
            ->method('retry')
            ->with(0, 0)
            ->willReturn('value');

        self::assertSame('value', retry([$this->retryer, 'retry'], 10));
    }

    public function testRetriedIfItFails(): void
    {
        $this->retryer
            ->method('retry')
            ->withConsecutive([0, 0], [1, 0])
            ->willReturnOnConsecutiveCalls(self::throwException(new Exception()), 'value');

        self::assertSame('value', retry([$this->retryer, 'retry'], 10));
    }

    public function testThrowsExceptionIfRetryCountIsReached(): void
    {
        $this->retryer
            ->method('retry')
            ->withConsecutive([0, 0], [1, 0])
            ->willReturnOnConsecutiveCalls(
                self::throwException(new Exception('first')),
                self::throwException(new Exception('second'))
            );

        $this->expectException('Exception');

        $this->expectExceptionMessage('second');
        retry([$this->retryer, 'retry'], 2);
    }

    public function testRetryWithEmptyDelaySequence(): void
    {
        $this->retryer
            ->method('retry')
            ->withConsecutive([0, 0], [1, 0])
            ->willReturnOnConsecutiveCalls(
                self::throwException(new Exception('first')),
                self::throwException(new Exception('second'))
            );

        $this->expectException('Exception');

        $this->expectExceptionMessage('second');
        retry([$this->retryer, 'retry'], 2, new ArrayIterator([]));
    }

    public function testThrowsExceptionIfRetryCountNotAtLeast1(): void
    {
        $this->expectArgumentError(
            'Functional\retry() expects parameter 2 to be an integer greater than or equal to 1'
        );
        retry([$this->retryer, 'retry'], 0);
    }

    public function testUsesDelayTraversableForSleeping(): void
    {
        $this->retryer
            ->method('retry')
            ->withConsecutive([0, 0], [1, 0])
            ->willReturnOnConsecutiveCalls(
                self::throwException(new Exception('first')),
                self::throwException(new Exception('second'))
            );

        $this->expectException('Exception');

        $this->expectExceptionMessage('second');
        retry([$this->retryer, 'retry'], 2);
    }

    public function testDelayerSmallerThanRetries(): void
    {
        $this->retryer
            ->method('retry')
            ->withConsecutive([0, 10], [1, 20], [2, 30], [3, 10])
            ->willReturnOnConsecutiveCalls(
                self::throwException(new Exception('first')),
                self::throwException(new Exception('second')),
                self::throwException(new Exception('third')),
                self::throwException(new Exception('fourth'))
            );

        $this->expectException('Exception');

        $this->expectExceptionMessage('fourth');
        retry([$this->retryer, 'retry'], 4, new ArrayIterator([10, 20, 30]));
    }
}
