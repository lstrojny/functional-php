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

use ErrorException;
use RuntimeException;
use function Functional\error_to_exception;

class ErrorToExceptionTest extends AbstractTestCase
{
    public function testErrorIsThrownAsException()
    {
        $fn = error_to_exception('strpos');

        $errorLevel = error_reporting();
        try {
            $fn([], 0);
            $this->fail('ErrorException expected');
        } catch (ErrorException $e) {
            $this->assertSame('strpos() expects parameter 1 to be string, array given', $e->getMessage());
            $this->assertSame($errorLevel, error_reporting());
        }
    }

    public function testFunctionIsWrapped()
    {
        $fn = error_to_exception('substr');

        $errorLevel = error_reporting();
        $this->assertSame('f', $fn('foo', 0, 1));
        $this->assertSame($errorLevel, error_reporting());
    }

    public function testExceptionsAreHandledTransparently()
    {
        $expectedException = new RuntimeException();
        $fn = error_to_exception(
            function () use ($expectedException) {
                throw $expectedException;
            }
        );

        $errorLevel = error_reporting();
        try {
            $fn();
            $this->fail('Exception expected');
        } catch (RuntimeException $e) {
            $this->assertSame($expectedException, $e);
            $this->assertSame($errorLevel, error_reporting());
        }
    }
}
