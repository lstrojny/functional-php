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
use function Functional\unique;

class UniqueTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->list = ['value1', 'value2', 'value1', 'value'];
        $this->listIterator = new ArrayIterator($this->list);
        $this->mixedTypesArray = [1, '1', '2', 2, '3', 4];
        $this->mixedTypesIterator = new ArrayIterator($this->mixedTypesArray);
        $this->hash = ['k1' => 'val1', 'k2' => 'val2', 'k3' => 'val2', 'k1' => 'val1'];
        $this->hashIterator = new ArrayIterator($this->hash);
    }

    public function testDefaultBehavior()
    {
        $this->assertSame([0 => 'value1', 1 => 'value2', 3 => 'value'], unique($this->list));
        $this->assertSame([0 => 'value1', 1 => 'value2', 3 => 'value'], unique($this->listIterator));
        $this->assertSame(['k1' => 'val1', 'k2' => 'val2'], unique($this->hash));
        $this->assertSame(['k1' => 'val1', 'k2' => 'val2'], unique($this->hashIterator));
        $fn = function($value, $key, $collection) {
            return $value;
        };
        $this->assertSame([0 => 'value1', 1 => 'value2', 3 => 'value'], unique($this->list, $fn));
        $this->assertSame([0 => 'value1', 1 => 'value2', 3 => 'value'], unique($this->listIterator, $fn));
        $this->assertSame(['k1' => 'val1', 'k2' => 'val2'], unique($this->hash, $fn));
        $this->assertSame(['k1' => 'val1', 'k2' => 'val2'], unique($this->hashIterator, $fn));
    }

    public function testUnifyingByClosure()
    {
        $fn = function($value, $key, $collection) {
            return $key === 0 ? 'zero' : 'else';
        };
        $this->assertSame([0 => 'value1', 1 => 'value2'], unique($this->list, $fn));
        $this->assertSame([0 => 'value1', 1 => 'value2'], unique($this->listIterator, $fn));
        $fn = function($value, $key, $collection) {
            return 0;
        };
        $this->assertSame(['k1' => 'val1'], unique($this->hash, $fn));
        $this->assertSame(['k1' => 'val1'], unique($this->hashIterator, $fn));
    }

    public function testUnifyingStrict()
    {
        $this->assertSame([0 => 1, 2 => '2', 4 => '3', 5 => 4], unique($this->mixedTypesArray, null, false));
        $this->assertSame([1, '1', '2', 2, '3', 4], unique($this->mixedTypesArray));
        $this->assertSame([0 => 1, 2 => '2', 4 => '3', 5 => 4], unique($this->mixedTypesIterator, null, false));
        $this->assertSame([1, '1', '2', 2, '3', 4], unique($this->mixedTypesIterator));

        $fn = function($value, $key, $collection) {
            return $value;
        };

        $this->assertSame([0 => 1, 2 => '2', 4 => '3', 5 => 4], unique($this->mixedTypesArray, $fn, false));
        $this->assertSame([1, '1', '2', 2, '3', 4], unique($this->mixedTypesArray, $fn));
        $this->assertSame([0 => 1, 2 => '2', 4 => '3', 5 => 4], unique($this->mixedTypesIterator, null, false));
        $this->assertSame([1, '1', '2', 2, '3', 4], unique($this->mixedTypesIterator, $fn));
    }

    public function testPassingNullAsCallback()
    {
        $this->assertSame([0 => 'value1', 1 => 'value2', 3 => 'value'], unique($this->list));
        $this->assertSame([0 => 'value1', 1 => 'value2', 3 => 'value'], unique($this->list, null));
        $this->assertSame([0 => 'value1', 1 => 'value2', 3 => 'value'], unique($this->list, null, false));
        $this->assertSame([0 => 'value1', 1 => 'value2', 3 => 'value'], unique($this->list, null, true));
    }

    public function testExceptionIsThrownInArray()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        unique($this->list, [$this, 'exception']);
    }

    public function testExceptionIsThrownInHash()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        unique($this->hash, [$this, 'exception']);
    }

    public function testExceptionIsThrownInIterator()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        unique($this->listIterator, [$this, 'exception']);
    }

    public function testExceptionIsThrownInHashIterator()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        unique($this->hashIterator, [$this, 'exception']);
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\unique() expects parameter 1 to be array or instance of Traversable');
        unique('invalidCollection', 'strlen');
    }

    public function testPassNonCallableUndefinedFunction()
    {
        $this->expectArgumentError("Argument 2 passed to Functional\unique() must be callable");
        unique($this->list, 'undefinedFunction');
    }
}
