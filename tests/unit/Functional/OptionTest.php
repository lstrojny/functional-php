<?php
/**
 * Copyright (C) 2011 - 2012 by Lars Strojny <lstrojny@php.net>
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

use ArrayIterator;

class OptionTest extends \PHPUnit_Framework_TestCase
{
    private function id()
    {
        return function($x) { return $x; };
    }
    private function inc($y)
    {
        return function($x) use ($y) { return $x + $y; };
    }
    private function idSome()
    {
        return function($x) { return new Some($x); };
    }
    private function incSome($y)
    {
        return function($x) use ($y) { return new Some($x + $y); };
    }
    private function idNone($x)
    {
        return function($x) { return new None(); };
    }
    private function say($s)
    {
        return function() use ($s) { return $s; };
    }

    function testConstructor()
    {
        $this->assertNone(option());
        $this->assertSome(option(1), 1);
    }

    function testGet()
    {
        $this->assertSame(42, option(42)->get());
    }

    function testNoneIsEmpty()
    {
        $this->assertTrue(option()->isEmpty());
        $this->assertFalse(option()->isNotEmpty());
    }

    function testSomeIsEmpty()
    {
        $this->assertFalse(option(42)->isEmpty());
        $this->assertTrue(option(42)->isNotEmpty());
    }

    function testNoneAppend()
    {
        $this->assertNone(option()->append(option()));
        $this->assertSome(option()->append(option(42)), 42);
    }

    function testSomeAppend()
    {
        $this->assertSome(option(42)->append(option()), 42);
        $this->assertSome(option(42)->append(option(10)), 52);
    }

    function testSomeAppendWithCustomCallback()
    {
        $callback = function($x, $y) { return $x * $y; };
        $this->assertSome(option(42)->append(option(10), $callback), 420);
        $this->assertSome(option(42)->append(option(), $callback), 42);
        $this->assertSome(option()->append(option(10), $callback), 10);
    }

    function testNoneMap()
    {
        $this->assertNone(option()->map($this->id()));
        $this->assertNone(option()->map($this->inc(3)));
    }

    function testSomeMap()
    {
        $this->assertSome(option(42)->map($this->id()), 42);
        $this->assertSome(option(42)->map($this->inc(3)), 45);
    }

    function testNoneBind()
    {
        $this->assertNone(option()->map($this->id()));
        $this->assertNone(option()->map($this->inc(3)));
    }

    function testSomeBind()
    {
        $this->assertSome(option(42)->bind($this->idSome(10)), 42);
        $this->assertSome(option(42)->bind($this->incSome(3)), 45);
        $this->assertNone(option(42)->bind($this->idNone(10)));
    }

    function testNoneFold()
    {
        $this->assertSame('boo', option()->fold($this->inc(5), $this->say('boo')));
    }

    function testSomeFold()
    {
        $this->assertSame(47, option(42)->fold($this->inc(5), $this->say('boo')));
    }

    function testNoneIterator()
    {
        foreach(option() as $x) {
            $this->fail();
        }
        $this->assertTrue(true);
    }

    function testSomeIterator()
    {
        $a = true;
        foreach(option(42) as $x) {
            $a = $x;
        }
        $this->assertSame(42, $a);
    }

    private function assertSome($some, $x)
    {
        $this->assertInstanceOf('Functional\\Some', $some);
        $this->assertSame($x, $some->get());
    }

    private function assertNone($none)
    {
        $this->assertInstanceOf('Functional\\None', $none);
    }
}
