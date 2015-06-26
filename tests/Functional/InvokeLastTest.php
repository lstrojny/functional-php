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
namespace Functional\Tests;

use ArrayIterator;
use function Functional\invoke_last;

class InvokeLastTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->list = [null, null, $this];
        $this->listIterator = new ArrayIterator($this->list);
        $this->keyArray = ['k1' => null, 'k2' => $this];
        $this->keyIterator = new ArrayIterator(['k1' => null, 'k2' => $this]);

        $this->arrayVeryLastNotCallable = [null, null, $this, null];
        $this->iteratorVeryLastNotCallable = new ArrayIterator($this->arrayVeryLastNotCallable);
    }

    public function testSimple()
    {
        $this->assertSame('methodValue', invoke_last($this->list, 'method', [1, 2]));
        $this->assertSame('methodValue', invoke_last($this->listIterator, 'method'));
        $this->assertSame(null, invoke_last($this->list, 'undefinedMethod'));
        $this->assertSame(null, invoke_last($this->list, 'setExpectedExceptionFromAnnotation'), 'Protected method');
        $this->assertSame([1, 2], invoke_last($this->list, 'returnArguments', [1, 2]));
        $this->assertSame('methodValue', invoke_last($this->keyArray, 'method'));
        $this->assertSame('methodValue', invoke_last($this->keyIterator, 'method'));
    }

    public function testSkipNonCallables()
    {
    	$this->assertSame('methodValue', invoke_last($this->arrayVeryLastNotCallable, 'method', [1, 2]));
    	$this->assertSame('methodValue', invoke_last($this->iteratorVeryLastNotCallable, 'method'));
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\invoke_last() expects parameter 1 to be array or instance of Traversable');
        invoke_last('invalidCollection', 'method');
    }

    public function testPassNoPropertyName()
    {
        $this->expectArgumentError('Functional\invoke_last() expects parameter 2 to be string');
        invoke_last($this->list, new \stdClass());
    }

    public function testException()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        invoke_last($this->list, 'exception');
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
