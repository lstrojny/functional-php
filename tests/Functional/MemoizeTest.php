<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use BadMethodCallException;
use PHPUnit\Framework\MockObject\MockObject;
use RuntimeException;

use function Functional\memoize;

function testfunc(): string
{
    return 'TESTFUNC' . MemoizeTest::invoke(__FUNCTION__);
}

class MemoizeTest extends AbstractTestCase
{
    private static $invocation = 0;

    /** @var MockObject */
    private $callback;

    public static function invoke($name): int
    {
        if (self::$invocation > 0) {
            throw new BadMethodCallException(\sprintf('%s called more than once', $name));
        }
        self::$invocation++;
        return self::$invocation;
    }

    public static function call(): string
    {
        return 'STATIC METHOD VALUE' . self::invoke(__METHOD__);
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->callback = $this->getMockBuilder('stdClass')
            ->setMethods(['execute'])
            ->getMock();

        self::$invocation = 0;
    }

    public function testMemoizeSimpleObjectCall(): void
    {
        $this->callback
            ->expects(self::once())
            ->method('execute')
            ->willReturn('VALUE1');

        self::assertSame('VALUE1', memoize([$this->callback, 'execute']));
        self::assertSame('VALUE1', memoize([$this->callback, 'execute']));
        self::assertSame('VALUE1', memoize([$this->callback, 'execute']));
    }

    public function testMemoizeFunctionCall(): void
    {
        self::assertSame('TESTFUNC1', memoize('Functional\Tests\testfunc'));
        self::assertSame('TESTFUNC1', memoize('Functional\Tests\testfunc'));
        self::assertSame('TESTFUNC1', memoize('Functional\Tests\testfunc'));
    }

    public function testMemoizeStaticMethodCall(): void
    {
        self::assertSame('STATIC METHOD VALUE1', memoize([MemoizeTest::class, 'call']));
        self::assertSame('STATIC METHOD VALUE1', memoize([MemoizeTest::class, 'call']));
        self::assertSame('STATIC METHOD VALUE1', memoize([MemoizeTest::class, 'call']));
    }

    public function testMemoizeClosureCall(): void
    {
        $closure = function () {
            return 'CLOSURE VALUE' . MemoizeTest::invoke('Closure');
        };
        self::assertSame('CLOSURE VALUE1', memoize($closure));
        self::assertSame('CLOSURE VALUE1', memoize($closure));
        self::assertSame('CLOSURE VALUE1', memoize($closure));
    }

    public function testMemoizeWithArguments(): void
    {
        $this->callback
            ->method('execute')
            ->withConsecutive(['FOO', 'BAR'], ['BAR', 'BAZ'])
            ->willReturnOnConsecutiveCalls('FOO BAR', 'BAR BAZ');

        self::assertSame('FOO BAR', memoize([$this->callback, 'execute'], ['FOO', 'BAR']));
        self::assertSame('FOO BAR', memoize([$this->callback, 'execute'], ['FOO', 'BAR']));
        self::assertSame('BAR BAZ', memoize([$this->callback, 'execute'], ['BAR', 'BAZ']));
        self::assertSame('BAR BAZ', memoize([$this->callback, 'execute'], ['BAR', 'BAZ']));
    }

    public function testMemoizeWithCustomKey(): void
    {
        $this->callback
            ->method('execute')
            ->withConsecutive(['FOO', 'BAR'], ['BAR', 'BAZ'])
            ->willReturnOnConsecutiveCalls('FOO BAR', 'BAR BAZ');

        self::assertSame('FOO BAR', memoize([$this->callback, 'execute'], ['FOO', 'BAR'], 'MY:CUSTOM:KEY'));
        self::assertSame('FOO BAR', memoize([$this->callback, 'execute'], ['BAR', 'BAZ'], 'MY:CUSTOM:KEY'), 'Result already memoized');

        self::assertSame('BAR BAZ', memoize([$this->callback, 'execute'], ['BAR', 'BAZ'], 'MY:DIFFERENT:KEY'));
        self::assertSame('BAR BAZ', memoize([$this->callback, 'execute'], ['BAR', 'BAZ'], 'MY:DIFFERENT:KEY'), 'Result already memoized');
        self::assertSame('BAR BAZ', memoize([$this->callback, 'execute'], ['FOO', 'BAR'], 'MY:DIFFERENT:KEY'), 'Result already memoized');
    }

    public function testResultIsNotStoredIfExceptionIsThrown(): void
    {
        $this->callback
            ->expects(self::exactly(2))
            ->method('execute')
            ->will(self::throwException(new BadMethodCallException('EXCEPTION')));

        try {
            memoize([$this->callback, 'execute']);
            self::fail('Expected failure');
        } catch (BadMethodCallException $e) {
        }

        try {
            memoize([$this->callback, 'execute']);
            self::fail('Expected failure');
        } catch (BadMethodCallException $e) {
        }
    }

    public function testResetByPassingNullAsCallable(): void
    {
        $this->callback
            ->expects(self::exactly(2))
            ->method('execute');

        memoize([$this->callback, 'execute']);
        memoize([$this->callback, 'execute']);

        self::assertNull(memoize(null));

        memoize([$this->callback, 'execute']);
        memoize([$this->callback, 'execute']);
    }

    public function testPassNoCallable(): void
    {
        $this->expectCallableArgumentError('Functional\memoize', 1);
        memoize('invalidFunction');
    }

    public function testSplObjectHashCollisions(): void
    {
        self::assertSame(0, memoize(self::createFn(0, 1)));
        self::assertSame(1, memoize(self::createFn(1, 1)));
        self::assertSame(2, memoize(self::createFn(2, 1)));
    }

    private static function createFn(int $id, int $number): callable
    {
        return new class ($id, $number) {
            private $id;
            private $expectedInvocations;
            private $actualInvocations = 0;

            public function __construct(int $id, int $expectedInvocations)
            {
                $this->id = $id;
                $this->expectedInvocations = $expectedInvocations;
            }

            public function getId(): int
            {
                return $this->id;
            }

            public function __invoke(): int
            {
                $this->actualInvocations++;
                if ($this->actualInvocations > $this->expectedInvocations) {
                    throw new RuntimeException(
                        \sprintf(
                            'ID %d: Expected %d invocations, got %d',
                            $this->id,
                            $this->expectedInvocations,
                            $this->actualInvocations
                        )
                    );
                }

                return $this->id;
            }

            public function __destruct()
            {
                if ($this->actualInvocations !== $this->expectedInvocations) {
                    throw new RuntimeException(
                        \sprintf(
                            'ID %d: Expected %d invocations, got %d',
                            $this->id,
                            $this->expectedInvocations,
                            $this->actualInvocations
                        )
                    );
                }
            }
        };
    }
}
