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

class InvokeAtTest extends AbstractTestCase
{
    function setUp()
    {
        parent::setUp(array('Functional\\invoke_at'));
        $this->array = array(null, $this, null);
        $this->iterator = new ArrayIterator($this->array);
        $this->keyArray = array('k1' => null, 'k2' => $this);
        $this->keyIterator = new ArrayIterator(array('k1' => $this, 'k2' => $this));
    }
       
    function test()
    {
        $this->assertSame('methodValue', invoke_at($this->array, 'method', 1, array(1, 2)));
        $this->assertSame('methodValue', invoke_at($this->iterator, 'method', 1, array(1, 2)));
        $this->assertSame(null, invoke_at($this->array, 'undefinedMethod', 1));
        $this->assertSame(null, invoke_at($this->array, 'setExpectedExceptionFromAnnotation', 1), 'Protected method');
        $this->assertSame(array(1, 2), invoke_at($this->array, 'returnArguments', 1, array(1, 2)));
        $this->assertSame('methodValue', invoke_at($this->keyArray, 'method', 'k2'));
        $this->assertSame('methodValue', invoke_at($this->keyIterator, 'method', 'k2'));
    }

    function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\invoke_at() expects parameter 1 to be array or instance of Traversable');
        invoke_at('invalidCollection', 'method', 0);
    } 

    function testPassNoPropertyName()
    {
        $this->expectArgumentError('Functional\invoke_at() expects parameter 2 to be string');
        invoke_at($this->array, new \stdClass(), 0);
    }
 
    function testException()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        invoke_at($this->array, 'exception', 1);
    }
    
    function testUnknownArrayKey()
    {
    	$this->expectArgumentError('Functional\invoke_at(): unknown key "not_there"');
    	invoke_at($this->array, 'method', 'not_there');
    }
    
    function testPassInvalidArrayKey()
    {
    	$this->expectArgumentError('Functional\invoke_at(): invalid array key of type "object". Expected NULL, string, integer, double or boolean');
    	invoke_at($this->array, 'method', new \stdClass());
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