<?php
/**
 * Copyright (C) 2011-2017 by Lars Strojny <lstrojny@php.net>
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

use function Functional\with;
use PHPUnit\Framework\Error\Deprecated as DeprecatedError;

class WithTest extends AbstractTestCase
{
    public function testWithNull()
    {
        $this->assertNull(
            with(null, function() {
                throw new \Exception('Should not be called');
            })
        );
    }

    public function testWithValue()
    {
        $this->assertSame(
            2,
            with(
                1,
                function ($value) {
                    return $value + 1;
                }
            )
        );
    }

    public function testWithCallback()
    {
        DeprecatedError::$enabled = false;

        $this->assertSame(
            'value',
            with(
                function() {
                    return 'value';
                },
                function ($value) {
                    return $value;
                }
            )
        );

        DeprecatedError::$enabled = true;
    }

    public function testPassNonCallable()
    {
        $this->expectArgumentError("Argument 2 passed to Functional\with() must be callable");
        with(null, 'undefinedFunction');
    }
}
