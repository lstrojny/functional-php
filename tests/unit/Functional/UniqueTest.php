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

class UniqueTest extends AbstractTestCase
{
    function setUp()
    {
        parent::setUp();
        $this->array = array('value1', 'value2', 'value1', 'value');
        $this->iterator = new ArrayIterator($this->array);
        $this->mixedTypesArray = array(1, '1', '2', 2, '3', 4);
        $this->mixedTypesIterator = new ArrayIterator($this->mixedTypesArray);
        $this->keyedArray = array('k1' => 'val1', 'k2' => 'val2', 'k3' => 'val2', 'k1' => 'val1');
        $this->keyedIterator = new ArrayIterator($this->keyedArray);
    }
   
    function testDefaultBehavior()
    {
        $this->assertSame(array(0 => 'value1', 1 => 'value2', 3 => 'value'), unique($this->array));
        $this->assertSame(array(0 => 'value1', 1 => 'value2', 3 => 'value'), unique($this->iterator));
        $this->assertSame(array('k1' => 'val1', 'k2' => 'val2'), unique($this->keyedArray));
        $this->assertSame(array('k1' => 'val1', 'k2' => 'val2'), unique($this->keyedIterator));
        $fn = function($value, $key, $collection) {
            return $value;
        };
        $this->assertSame(array(0 => 'value1', 1 => 'value2', 3 => 'value'), unique($this->array, $fn));
        $this->assertSame(array(0 => 'value1', 1 => 'value2', 3 => 'value'), unique($this->iterator, $fn));
        $this->assertSame(array('k1' => 'val1', 'k2' => 'val2'), unique($this->keyedArray, $fn));
        $this->assertSame(array('k1' => 'val1', 'k2' => 'val2'), unique($this->keyedIterator, $fn));
    }

    function testUnifyingByClosure()
    {
        $fn = function($value, $key, $collection) {
            return $key === 0 ? 'zero' : 'else';
        };
        $this->assertSame(array(0 => 'value1', 1 => 'value2'), unique($this->array, $fn));
        $this->assertSame(array(0 => 'value1', 1 => 'value2'), unique($this->iterator, $fn));
        $fn = function($value, $key, $collection) {
            return 0;
        };
        $this->assertSame(array('k1' => 'val1'), unique($this->keyedArray, $fn));
        $this->assertSame(array('k1' => 'val1'), unique($this->keyedIterator, $fn));
    }
    
    function testUnifyingStrict()
    {
    	$this->assertSame(array(0 => 1, 2 => '2', 4 => '3', 5 => 4), unique($this->mixedTypesArray));
    	$this->assertSame(array(1, '1', '2', 2, '3', 4), unique($this->mixedTypesArray, null, true));
    	$this->assertSame(array(0 => 1, 2 => '2', 4 => '3', 5 => 4), unique($this->mixedTypesIterator));
    	$this->assertSame(array(1, '1', '2', 2, '3', 4), unique($this->mixedTypesIterator, null, true));
    	
        $fn = function($value, $key, $collection) {
            return $value;
        };
        
    	$this->assertSame(array(0 => 1, 2 => '2', 4 => '3', 5 => 4), unique($this->mixedTypesArray, $fn));
    	$this->assertSame(array(1, '1', '2', 2, '3', 4), unique($this->mixedTypesArray, $fn, true));
    	$this->assertSame(array(0 => 1, 2 => '2', 4 => '3', 5 => 4), unique($this->mixedTypesIterator));
    	$this->assertSame(array(1, '1', '2', 2, '3', 4), unique($this->mixedTypesIterator, $fn, true));
    }

    function testExceptionIsThrownInArray()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        unique($this->array, array($this, 'exception'));
    }

    function testExceptionIsThrownInKeyedArray()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        unique($this->keyedArray, array($this, 'exception'));
    }

    function testExceptionIsThrownInIterator()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        unique($this->iterator, array($this, 'exception'));
    }

    function testExceptionIsThrownInKeyedIterator()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        unique($this->keyedIterator, array($this, 'exception'));
    }

    function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\unique() expects parameter 1 to be array or instance of Traversable');
        unique('invalidCollection', 'strlen');
    }

    function testPassNonCallableUndefinedFunction()
    {
        $this->expectArgumentError("Functional\unique() expects parameter 2 to be a valid callback, function 'undefinedFunction' not found or invalid function name");
        unique($this->array, 'undefinedFunction');
    }
    
    function testPassNonCallableNull()
    {
        $this->expectArgumentError("Functional\unique() expects parameter 2 to be a valid callback, no array or string given");
        unique($this->array, null);
    }    
}