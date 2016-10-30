<?php
/**
 * Copyright (C) 2011-2016 by Lars Strojny <lstrojny@php.net>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the 'Software'), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace Functional\Tests;

use RuntimeException;
use function Functional\error_to_exception;

class ErrorToExceptionTest extends AbstractTestCase
{
    public function testErrorIsThrownAsException()
    {
        $fn = error_to_exception('strpos');

        $this->expectException('ErrorException');
        $this->expectExceptionMessage('strpos() expects parameter 1 to be string, array given');
        $fn([], 0);
    }

    public function testFunctionIsWrapped()
    {
        $fn = error_to_exception('substr');
        $this->assertSame('f', $fn('foo', 0, 1));
    }

    public function testExceptionsAreHandledTransparently()
    {
        $e = new RuntimeException();
        $fn = error_to_exception(
            function () use ($e) {
                throw $e;
            }
        );

        $this->expectException(RuntimeException::class);
        $fn();
    }
}
