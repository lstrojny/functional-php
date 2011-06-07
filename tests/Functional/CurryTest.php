<?php
/**
 * Copyright (C) 2011 by Lars Strojny <lstrojny@php.net>
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

class CurryTest extends AbstractTestCase
{
    function testSimpleCurrying()
    {
        $func = curry('strpos', 'foo', arg(1));
        $this->assertSame(1, $func('o'));
    }

    function testCurryingWithParameterReplacement()
    {
        $func = curry('strpos', arg(1), 'o');
        $this->assertSame(1, $func('foo'));
    }

    function testNestedCurrying()
    {
        $func = curry('strpos', arg(1), 'o');
        $func = curry($func, 'foo');
        $this->assertSame(1, $func());
    }

    function testCurryingVariableArguments()
    {
        $func = curry('sprintf', 'first: %d, second: %d, third: %d', arg('...'));
        $this->assertSame('first: 1, second: 2, third: 3', $func(1, 2, 3));

        $this->setExpectedException('Exception', 'sprintf(): Too few arguments');
        $this->assertSame('first: 1, second: 2, third: 3', $func(1, 2));
    }

    function testExceptionIsThrownWhenRequiredParameterIsNotPassed()
    {
        $func = curry('strpos', arg(1), arg(2));
        $this->setExpectedException('InvalidArgumentException', 'Curried strpos() requires parameter 2 to be passed. None given');
        $func('foo');
    }
}
