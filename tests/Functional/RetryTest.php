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
        $this->retryer = $this->createMock('Functional\Tests\Retryer');
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
            ->method('retry')
            ->withConsecutive([0, 0], [1, 0])
            ->willReturnOnConsecutiveCalls($this->throwException(new Exception()), 'value');

        $this->assertSame('value', retry([$this->retryer, 'retry'], 10));
    }

    public function testThrowsExceptionIfRetryCountIsReached()
    {
        $this->retryer
            ->method('retry')
            ->withConsecutive([0, 0], [1, 0])
            ->willReturnOnConsecutiveCalls(
                $this->throwException(new Exception('first')),
                $this->throwException(new Exception('second'))
            );

        $this->expectException('Exception');

        $this->expectExceptionMessage('second');
        retry([$this->retryer, 'retry'], 2);
    }

    public function testRetryWithEmptyDelaySequence()
    {
        $this->retryer
            ->method('retry')
            ->withConsecutive([0, 0], [1, 0])
            ->willReturnOnConsecutiveCalls(
                $this->throwException(new Exception('first')),
                $this->throwException(new Exception('second'))
            );

        $this->expectException('Exception');

        $this->expectExceptionMessage('second');
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
            ->method('retry')
            ->withConsecutive([0, 0], [1, 0])
            ->willReturnOnConsecutiveCalls(
                $this->throwException(new Exception('first')),
                $this->throwException(new Exception('second'))
            );

        $this->expectException('Exception');

        $this->expectExceptionMessage('second');
        retry([$this->retryer, 'retry'], 2);
    }

    public function testDelayerSmallerThanRetries()
    {
        $this->retryer
            ->method('retry')
            ->withConsecutive([0, 10], [1, 20], [2, 30], [3, 10])
            ->willReturnOnConsecutiveCalls(
                $this->throwException(new Exception('first')),
                $this->throwException(new Exception('second')),
                $this->throwException(new Exception('third')),
                $this->throwException(new Exception('fourth'))
            );

        $this->expectException('Exception');

        $this->expectExceptionMessage('fourth');
        retry([$this->retryer, 'retry'], 4, new ArrayIterator([10, 20, 30]));
    }
}
