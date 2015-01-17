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
        $this->hash = array('k1' => 'val1', 'k2' => 'val2', 'k3' => 'val2', 'k1' => 'val1');
        $this->hashIterator = new ArrayIterator($this->hash);
    }

    function testDefaultBehavior()
    {
        $this->assertSame(array(0 => 'value1', 1 => 'value2', 3 => 'value'), unique($this->array));
        $this->assertSame(array(0 => 'value1', 1 => 'value2', 3 => 'value'), unique($this->iterator));
        $this->assertSame(array('k1' => 'val1', 'k2' => 'val2'), unique($this->hash));
        $this->assertSame(array('k1' => 'val1', 'k2' => 'val2'), unique($this->hashIterator));
        $fn = function($value, $key, $collection) {
            return $value;
        };
        $this->assertSame(array(0 => 'value1', 1 => 'value2', 3 => 'value'), unique($this->array, $fn));
        $this->assertSame(array(0 => 'value1', 1 => 'value2', 3 => 'value'), unique($this->iterator, $fn));
        $this->assertSame(array('k1' => 'val1', 'k2' => 'val2'), unique($this->hash, $fn));
        $this->assertSame(array('k1' => 'val1', 'k2' => 'val2'), unique($this->hashIterator, $fn));
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
        $this->assertSame(array('k1' => 'val1'), unique($this->hash, $fn));
        $this->assertSame(array('k1' => 'val1'), unique($this->hashIterator, $fn));
    }

    function testUnifyingStrict()
    {
        $this->assertSame(array(0 => 1, 2 => '2', 4 => '3', 5 => 4), unique($this->mixedTypesArray, null, false));
        $this->assertSame(array(1, '1', '2', 2, '3', 4), unique($this->mixedTypesArray));
        $this->assertSame(array(0 => 1, 2 => '2', 4 => '3', 5 => 4), unique($this->mixedTypesIterator, null, false));
        $this->assertSame(array(1, '1', '2', 2, '3', 4), unique($this->mixedTypesIterator));

        $fn = function($value, $key, $collection) {
            return $value;
        };

        $this->assertSame(array(0 => 1, 2 => '2', 4 => '3', 5 => 4), unique($this->mixedTypesArray, $fn, false));
        $this->assertSame(array(1, '1', '2', 2, '3', 4), unique($this->mixedTypesArray, $fn));
        $this->assertSame(array(0 => 1, 2 => '2', 4 => '3', 5 => 4), unique($this->mixedTypesIterator, null, false));
        $this->assertSame(array(1, '1', '2', 2, '3', 4), unique($this->mixedTypesIterator, $fn));
    }

    function testPassingNullAsCallback()
    {
        $this->assertSame(array(0 => 'value1', 1 => 'value2', 3 => 'value'), unique($this->array));
        $this->assertSame(array(0 => 'value1', 1 => 'value2', 3 => 'value'), unique($this->array, null));
        $this->assertSame(array(0 => 'value1', 1 => 'value2', 3 => 'value'), unique($this->array, null, false));
        $this->assertSame(array(0 => 'value1', 1 => 'value2', 3 => 'value'), unique($this->array, null, true));
    }

    function testExceptionIsThrownInArray()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        unique($this->array, array($this, 'exception'));
    }

    function testExceptionIsThrownInHash()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        unique($this->hash, array($this, 'exception'));
    }

    function testExceptionIsThrownInIterator()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        unique($this->iterator, array($this, 'exception'));
    }

    function testExceptionIsThrownInHashIterator()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        unique($this->hashIterator, array($this, 'exception'));
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
}
