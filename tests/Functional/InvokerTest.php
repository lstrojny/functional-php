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

use function Functional\invoker;

class InvokerTest extends AbstractTestCase
{
    public function testInvokerWithoutArguments()
    {
        $fn = invoker('valueMethod');
        $this->assertSame('value', $fn($this));
    }

    public function testInvokerWithArguments()
    {
        $arguments = [1, 2, 3];
        $fn = invoker('argumentMethod', $arguments);
        $this->assertSame($arguments, $fn($this));
    }

    public function testPassNoString()
    {
        $this->expectArgumentError('Functional\invoker() expects parameter 1 to be string');
        invoker([]);
    }

    public function testInvalidMethod()
    {
        if (!class_exists('Error')) {
            $this->markTestSkipped('Requires PHP 7');
        }

        $fn = invoker('undefinedMethod');

        $this->expectException('Error', 'Call to undefined method Functional\\Tests\\InvokerTest::undefinedMethod');
        $fn($this);
    }

    public function valueMethod(...$arguments)
    {
        $this->assertEmpty($arguments);

        return 'value';
    }

    public function argumentMethod(...$arguments)
    {
        $this->assertNotEmpty($arguments);

        return $arguments;
    }
}
