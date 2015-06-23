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

class ReduceTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp('Functional\reduce_right', 'Functional\reduce_left');
        $this->array = array('one', 'two', 'three');
        $this->iterator = new ArrayIterator($this->array);
    }

    public function testReducing()
    {
        $this->currentCollection = $this->array;
        $this->assertSame('0:one,1:two,2:three', reduce_left($this->array, array($this, 'functionalCallback')));
        $this->assertSame('default,0:one,1:two,2:three', reduce_left($this->array, array($this, 'functionalCallback'), 'default'));
        $this->assertSame('2:three,1:two,0:one', reduce_right($this->array, array($this, 'functionalCallback')));
        $this->assertSame('default,2:three,1:two,0:one', reduce_right($this->array, array($this, 'functionalCallback'), 'default'));

        $this->currentCollection = $this->iterator;
        $this->assertSame('0:one,1:two,2:three', reduce_left($this->iterator, array($this, 'functionalCallback')));
        $this->assertSame('default,0:one,1:two,2:three', reduce_left($this->iterator, array($this, 'functionalCallback'), 'default'));
        $this->assertSame('2:three,1:two,0:one', reduce_right($this->iterator, array($this, 'functionalCallback')));
        $this->assertSame('default,2:three,1:two,0:one', reduce_right($this->iterator, array($this, 'functionalCallback'), 'default'));

        $this->assertSame('initial', reduce_left(array(), function(){}, 'initial'));
        $this->assertNull(reduce_left(array(), function(){}));
        $this->assertNull(reduce_left(array(), function(){}, null));
        $this->assertSame('initial', reduce_left(new ArrayIterator(array()), function(){}, 'initial'));
        $this->assertNull(reduce_left(new ArrayIterator(array()), function(){}));
        $this->assertNull(reduce_left(new ArrayIterator(array()), function(){}, null));
        $this->assertSame('initial', reduce_right(array(), function(){}, 'initial'));
        $this->assertNull(reduce_right(array(), function(){}));
        $this->assertNull(reduce_right(array(), function(){}, null));
        $this->assertSame('initial', reduce_right(new ArrayIterator(array()), function(){}, 'initial'));
        $this->assertNull(reduce_right(new ArrayIterator(array()), function(){}));
        $this->assertNull(reduce_right(new ArrayIterator(array()), function(){}, null));
   }

    public function testExceptionThrownInIteratorCallbackWhileReduceLeft()
    {
        $this->setExpectedException('DomainException', 'Callback exception: 0');
        reduce_left($this->iterator, array($this, 'exception'));
    }

    public function testExceptionThrownInIteratorCallbackWhileReduceRight()
    {
        $this->setExpectedException('DomainException', 'Callback exception: 2');
        reduce_right($this->iterator, array($this, 'exception'));
    }

    public function testExceptionThrownInArrayCallbackWhileReduceLeft()
    {
        $this->setExpectedException('DomainException', 'Callback exception: 0');
        reduce_left($this->array, array($this, 'exception'));
    }

    public function testExceptionThrownInArrayCallbackWhileReduceRight()
    {
        $this->setExpectedException('DomainException', 'Callback exception: 2');
        reduce_right($this->array, array($this, 'exception'));
    }

    public function functionalCallback($value, $key, $collection, $returnValue)
    {
        $this->assertContains($value, $this->currentCollection);
        $this->assertTrue(isset($this->currentCollection[$key]));
        $this->assertSame($collection, $this->currentCollection);

        $ret = $key . ':' . $value;
        if ($returnValue) {
            return $returnValue . ',' . $ret;
        }
        return $ret;
    }

    public function testPassNoCollectionToReduceLeft()
    {
        $this->expectArgumentError('Functional\reduce_left() expects parameter 1 to be array or instance of Traversable');
        reduce_left('invalidCollection', 'strlen');
    }

    public function testPassNonCallableToReduceLeft()
    {
        $this->expectArgumentError("Functional\\reduce_left() expects parameter 2 to be a valid callback, function 'undefinedFunction' not found or invalid function name");
        reduce_left($this->array, 'undefinedFunction');
    }

    public function testPassNoCollectionToReduceRight()
    {
        $this->expectArgumentError('Functional\reduce_right() expects parameter 1 to be array or instance of Traversable');
        reduce_right('invalidCollection', 'strlen');
    }

    public function testPassNonCallableToReduceRight()
    {
        $this->expectArgumentError("Functional\\reduce_right() expects parameter 2 to be a valid callback, function 'undefinedFunction' not found or invalid function name");
        reduce_right($this->array, 'undefinedFunction');
    }
}
