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

class ContainsTest extends AbstractTestCase
{
    function setUp()
    {
        parent::setUp();
        $this->array = array('value0', 'value1', 'value2', 2);
        $this->iterator = new ArrayIterator($this->array);
        $this->hash = array('k1' => 'val1', 'k2' => 'val2', 'k3' => 'val3', 'k4' => 2);
        $this->hashIterator = new ArrayIterator($this->hash);
    }

    function test()
    {
        $this->assertFalse(contains(array(), 'foo'));
        $this->assertFalse(contains(new ArrayIterator(), 'foo'));

        $this->assertTrue(contains($this->array, 'value0'));
        $this->assertTrue(contains($this->array, 'value1'));
        $this->assertTrue(contains($this->array, 'value2'));
        $this->assertTrue(contains($this->array, 2));
        $this->assertFalse(contains($this->array, '2', true));
        $this->assertFalse(contains($this->array, '2'));
        $this->assertTrue(contains($this->array, '2', false));
        $this->assertFalse(contains($this->array, 'value'));

        $this->assertTrue(contains($this->iterator, 'value0'));
        $this->assertTrue(contains($this->iterator, 'value1'));
        $this->assertTrue(contains($this->iterator, 'value2'));
        $this->assertTrue(contains($this->iterator, 2));
        $this->assertFalse(contains($this->iterator, '2', true));
        $this->assertFalse(contains($this->iterator, '2'));
        $this->assertTrue(contains($this->iterator, '2', false));
        $this->assertFalse(contains($this->iterator, 'value'));

        $this->assertTrue(contains($this->hash, 'val1'));
        $this->assertTrue(contains($this->hash, 'val2'));
        $this->assertTrue(contains($this->hash, 'val3'));
        $this->assertTrue(contains($this->hash, 2));
        $this->assertFalse(contains($this->hash, '2', true));
        $this->assertFalse(contains($this->hash, '2'));
        $this->assertTrue(contains($this->hash, '2', false));
        $this->assertFalse(contains($this->hash, 'value'));

        $this->assertTrue(contains($this->hashIterator, 'val1'));
        $this->assertTrue(contains($this->hashIterator, 'val2'));
        $this->assertTrue(contains($this->hashIterator, 'val3'));
        $this->assertTrue(contains($this->hashIterator, 2));
        $this->assertFalse(contains($this->hashIterator, '2', true));
        $this->assertFalse(contains($this->hashIterator, '2'));
        $this->assertTrue(contains($this->hashIterator, '2', false));
        $this->assertFalse(contains($this->hashIterator, 'value'));
    }

    function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\contains() expects parameter 1 to be array or instance of Traversable');
        contains('invalidCollection', 'value');
    }
}
