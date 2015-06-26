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

use ArrayIterator;
use function Functional\invoke;

class InvokeTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->list = [$this, $this, $this];
        $this->listIterator = new ArrayIterator($this->list);
        $this->keyArray = ['k1' => $this, 'k2' => $this];
        $this->keyIterator = new ArrayIterator(['k1' => $this, 'k2' => $this]);
    }

    public function test()
    {
        $this->assertSame(['methodValue', 'methodValue', 'methodValue'], invoke($this->list, 'method', [1, 2]));
        $this->assertSame(['methodValue', 'methodValue', 'methodValue'], invoke($this->listIterator, 'method'));
        $this->assertSame([null, null, null], invoke($this->list, 'undefinedMethod'));
        $this->assertSame([null, null, null], invoke($this->list, 'setExpectedExceptionFromAnnotation'), 'Protected method');
        $this->assertSame([[1, 2], [1, 2], [1, 2]], invoke($this->list, 'returnArguments', [1, 2]));
        $this->assertSame(['k1' => 'methodValue', 'k2' => 'methodValue'], invoke($this->keyArray, 'method'));
        $this->assertSame(['k1' => 'methodValue', 'k2' => 'methodValue'], invoke($this->keyIterator, 'method'));
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\invoke() expects parameter 1 to be array or instance of Traversable');
        invoke('invalidCollection', 'method');
    }

    public function testPassNoPropertyName()
    {
        $this->expectArgumentError('Functional\invoke() expects parameter 2 to be string');
        invoke($this->list, new \stdClass());
    }

    public function testException()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        invoke($this->list, 'exception');
    }

    public function method()
    {
        return 'methodValue';
    }

    public function returnArguments()
    {
        return func_get_args();
    }
}
