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

use PHPUnit_Framework_MockObject_MockObject as MockObject;
use BadMethodCallException;
use function Functional\memoize;

function testfunc()
{
    return 'TESTFUNC' . MemoizeTest::invoke(__FUNCTION__);
}

class MemoizeTest extends AbstractTestCase
{
    private static $invocation = 0;

    /** @var MockObject */
    private $callback;

    public static function invoke($name)
    {
        if (self::$invocation > 0) {
            throw new BadMethodCallException(sprintf('%s called more than once', $name));
        }
        self::$invocation++;
        return self::$invocation;
    }

    public static function call()
    {
        return 'STATIC METHOD VALUE' . self::invoke(__METHOD__);
    }

    public function setUp()
    {
        parent::setUp();
        $this->callback = $this->getMockBuilder('stdClass')
            ->setMethods(['execute'])
            ->getMock();

        self::$invocation = 0;
    }

    public function testMemoizeSimpleObjectCall()
    {
        $this->callback
            ->expects($this->once())
            ->method('execute')
            ->will($this->returnValue('VALUE1'));

        $this->assertSame('VALUE1', memoize([$this->callback, 'execute']));
        $this->assertSame('VALUE1', memoize([$this->callback, 'execute']));
        $this->assertSame('VALUE1', memoize([$this->callback, 'execute']));
    }

    public function testMemoizeFunctionCall()
    {
        $this->assertSame('TESTFUNC1', memoize('Functional\Tests\testfunc'));
        $this->assertSame('TESTFUNC1', memoize('Functional\Tests\testfunc'));
        $this->assertSame('TESTFUNC1', memoize('Functional\Tests\testfunc'));
    }

    public function testMemoizeStaticMethodCall()
    {
        $this->assertSame('STATIC METHOD VALUE1', memoize(['Functional\Tests\MemoizeTest', 'call']));
        $this->assertSame('STATIC METHOD VALUE1', memoize(['Functional\Tests\MemoizeTest', 'call']));
        $this->assertSame('STATIC METHOD VALUE1', memoize(['Functional\Tests\MemoizeTest', 'call']));
    }

    public function testMemoizeClosureCall()
    {
        $closure = function() {
            return 'CLOSURE VALUE' . MemoizeTest::invoke('Closure');
        };
        $this->assertSame('CLOSURE VALUE1', memoize($closure));
        $this->assertSame('CLOSURE VALUE1', memoize($closure));
        $this->assertSame('CLOSURE VALUE1', memoize($closure));
    }

    public function testMemoizeWithArguments()
    {
        $this->callback
            ->expects($this->at(0))
            ->method('execute')
            ->with('FOO', 'BAR')
            ->will($this->returnValue('FOO BAR'));

        $this->callback
            ->expects($this->at(1))
            ->method('execute')
            ->with('BAR', 'BAZ')
            ->will($this->returnValue('BAR BAZ'));

        $this->assertSame('FOO BAR', memoize([$this->callback, 'execute'], ['FOO', 'BAR']));
        $this->assertSame('FOO BAR', memoize([$this->callback, 'execute'], ['FOO', 'BAR']));
        $this->assertSame('BAR BAZ', memoize([$this->callback, 'execute'], ['BAR', 'BAZ']));
        $this->assertSame('BAR BAZ', memoize([$this->callback, 'execute'], ['BAR', 'BAZ']));
    }

    public function testMemoizeWithCustomKey()
    {
        $this->callback
            ->expects($this->at(0))
            ->method('execute')
            ->with('FOO', 'BAR')
            ->will($this->returnValue('FOO BAR'));

        $this->callback
            ->expects($this->at(1))
            ->method('execute')
            ->with('BAR', 'BAZ')
            ->will($this->returnValue('BAR BAZ'));

        $this->assertSame('FOO BAR', memoize([$this->callback, 'execute'], ['FOO', 'BAR'], 'MY:CUSTOM:KEY'));
        $this->assertSame('FOO BAR', memoize([$this->callback, 'execute'], ['BAR', 'BAZ'], 'MY:CUSTOM:KEY'), 'Result already memoized');
        $this->assertSame('FOO BAR', memoize([$this->callback, 'execute'], ['BAR', 'BAZ'], ['MY', 'CUSTOM', 'KEY']), 'Result already memoized');

        $this->assertSame('BAR BAZ', memoize([$this->callback, 'execute'], ['BAR', 'BAZ'], 'MY:DIFFERENT:KEY'));
        $this->assertSame('BAR BAZ', memoize([$this->callback, 'execute'], ['BAR', 'BAZ'], 'MY:DIFFERENT:KEY'), 'Result already memoized');
        $this->assertSame('BAR BAZ', memoize([$this->callback, 'execute'], ['FOO', 'BAR'], 'MY:DIFFERENT:KEY'), 'Result already memoized');
    }

    public function testResultIsNotStoredIfExceptionIsThrown()
    {
        $this->callback
            ->expects($this->exactly(2))
            ->method('execute')
            ->will($this->throwException(new BadMethodCallException('EXCEPTION')));

        try {
            memoize([$this->callback, 'execute']);
            $this->fail('Expected failure');
        } catch (BadMethodCallException $e) {
        }

        try {
            memoize([$this->callback, 'execute']);
            $this->fail('Expected failure');
        } catch (BadMethodCallException $e) {
        }
    }

    public function testPassKeyGeneratorCallable()
    {
        $this->callback
            ->expects($this->exactly(2))
            ->method('execute');

        $keyGenerator = function () {
            static $index;
            return ($index++ % 2) === 0;
        };

        memoize([$this->callback, 'execute'], $keyGenerator);
        memoize([$this->callback, 'execute'], [], $keyGenerator);
        memoize([$this->callback, 'execute'], [], $keyGenerator);
        memoize([$this->callback, 'execute'], $keyGenerator);
        memoize([$this->callback, 'execute'], $keyGenerator);
        memoize([$this->callback, 'execute'], [], $keyGenerator);
    }

    public function testResetByPassingNullAsCallable()
    {
        $this->callback
            ->expects($this->exactly(2))
            ->method('execute');

        memoize([$this->callback, 'execute']);
        memoize([$this->callback, 'execute']);

        $this->assertNull(memoize(null));

        memoize([$this->callback, 'execute']);
        memoize([$this->callback, 'execute']);
    }

    public function testPassNoCallable()
    {
        $this->expectArgumentError('Argument 1 passed to Functional\memoize() must be callable');
        memoize('invalidFunction');
    }
}
