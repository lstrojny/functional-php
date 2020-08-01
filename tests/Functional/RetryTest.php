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

        $this->expectException('Exception');

        $this->expectExceptionMessage('second');
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
            ->expects($this->at(0))
            ->method('retry')
            ->with(0, 0)
            ->willThrowException(new Exception('first'));
        $this->retryer
            ->expects($this->at(1))
            ->method('retry')
            ->with(1, 0)
            ->willThrowException(new Exception('second'));

        $this->expectException('Exception');

        $this->expectExceptionMessage('second');
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

        $this->expectException('Exception');

        $this->expectExceptionMessage('four');
        retry([$this->retryer, 'retry'], 4, new ArrayIterator([1 => 10, 2 => 20]));
    }
}
