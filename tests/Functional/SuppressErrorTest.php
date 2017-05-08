<?php
/**
 * Copyright (C) 2011-2017 by Lars Strojny <lstrojny@php.net>
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
use function Functional\suppress_error;

class SuppressErrorTest extends AbstractTestCase
{
    public function testErrorIsSuppressed()
    {
        $fn = suppress_error('strpos');

        $this->assertNull($fn([], 0));
    }

    public function testFunctionIsWrapped()
    {
        $fn = suppress_error('substr');

        $this->assertSame('f', $fn('foo', 0, 1));
    }

    public function testExceptionsAreHandledTransparently()
    {
        $expectedException = new RuntimeException();
        $fn = suppress_error(
            function () use ($expectedException) {
                throw $expectedException;
            }
        );

        $this->expectException(RuntimeException::class);

        $fn();
    }

    public function testErrorHandlerNestingWorks()
    {
        $errorMessage = null;
        set_error_handler(
            static function($level, $message) use (&$errorMessage) {
                $errorMessage = $message;
            }
        );

        $fn = suppress_error('strpos');
        $this->assertNull($fn([], 0));

        strpos([], 0);
        $this->assertSame('strpos() expects parameter 1 to be string, array given', $errorMessage);
        restore_error_handler();
    }
}
