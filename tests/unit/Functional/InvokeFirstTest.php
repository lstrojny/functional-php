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

class InvokeFirstTest extends AbstractTestCase
{
    function setUp()
    {
        parent::setUp(array('Functional\\invoke_first'));
        $this->array = array($this, null, null);
        $this->iterator = new ArrayIterator($this->array);
        $this->keyArray = array('k1' => $this, 'k2' => null);
        $this->keyIterator = new ArrayIterator(array('k1' => $this, 'k2' => null));
        
        $this->arrayVeryFirstNotCallable = array(null, $this, null, null);
        $this->iteratorVeryFirstNotCallable = new ArrayIterator($this->arrayVeryFirstNotCallable);
    }

    function testSimple()
    {
        $this->assertSame('methodValue', invoke_first($this->array, 'method', array(1, 2)));
        $this->assertSame('methodValue', invoke_first($this->iterator, 'method'));
        $this->assertSame(null, invoke_first($this->array, 'undefinedMethod'));
        $this->assertSame(null, invoke_first($this->array, 'setExpectedExceptionFromAnnotation'), 'Protected method');
        $this->assertSame(array(1, 2), invoke_first($this->array, 'returnArguments', array(1, 2)));
        $this->assertSame('methodValue', invoke_first($this->keyArray, 'method'));
        $this->assertSame('methodValue', invoke_first($this->keyIterator, 'method'));
    }
    
    function testSkipNonCallables()
    {
    	$this->assertSame('methodValue', invoke_first($this->arrayVeryFirstNotCallable, 'method', array(1, 2)));
    	$this->assertSame('methodValue', invoke_first($this->iteratorVeryFirstNotCallable, 'method'));
        $this->assertSame(null, invoke_first($this->arrayVeryFirstNotCallable, 'undefinedMethod'));
        $this->assertSame(null, invoke_first($this->arrayVeryFirstNotCallable, 'setExpectedExceptionFromAnnotation'), 'Protected method');
        $this->assertSame(array(1, 2), invoke_first($this->arrayVeryFirstNotCallable, 'returnArguments', array(1, 2)));
    }

    function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\invoke_first() expects parameter 1 to be array or instance of Traversable');
        invoke_first('invalidCollection', 'method');
    }

    function testPassNoPropertyName()
    {
        $this->expectArgumentError('Functional\invoke_first() expects parameter 2 to be string');
        invoke_first($this->array, new \stdClass());
    }

    function testException()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        invoke_first($this->array, 'exception');
    }

    function method()
    {
        return 'methodValue';
    }

    function returnArguments()
    {
        return func_get_args();
    }
}