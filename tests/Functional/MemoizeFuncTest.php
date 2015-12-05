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

use function Functional\memoize_func;
use function Functional\reduce_left;

class MemoizeFuncTest extends AbstractTestCase
{
    public function testShouldThrowAnErrorIfNoCallableIsGiven()
    {
        $thrownException = 'PHPUnit_Framework_Error';

        if (version_compare(PHP_VERSION, '7.0.0', '>=')) {
            $thrownException = 'TypeError';
        }

        $this->setExpectedException($thrownException);

        memoize_func();
    }

    /**
     * @dataProvider operands
     */
    public function testShouldBeAbleToCacheTheResultOfACallable(...$arguments)
    {
        $hasBeenCalled = false;

        $fn = memoize_func(function(...$arguments) use (&$hasBeenCalled) {
            $hasBeenCalled = true;
            return reduce_left($arguments, function($value, $index, $collection, $reduction) {
                return $reduction + $value;
            }, 0);
        });

        $this->assertInternalType('callable', $fn);

        $expected = $fn(...$arguments);

        $this->assertTrue($hasBeenCalled);

        $hasBeenCalled = false;

        $actual = $fn(...$arguments);

        $this->assertFalse($hasBeenCalled);

        $this->assertSame($expected, $actual);
    }

    public static function operands()
    {
        return [
            [1, 2],
            [1, 2, 3],
            [1, 2, 3, 4],
            [1, 2, 3, 4, 5],
        ];
    }
}
