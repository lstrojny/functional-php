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
use stdClass;

class AverageTest extends AbstractTestCase
{
    function setUp()
    {
        parent::setUp();
        $this->hash = array("f0" => 12, "f1" => 2, "f3" => true, "f4" => false, "f5" => "str", "f6" => array(), "f7" => new stdClass(), "f8" => 1);
        $this->hashIterator = new ArrayIterator($this->hash);
        $this->array = array_values($this->hash);
        $this->arrayIterator = new ArrayIterator($this->array);

        $this->hash2 = array("f0" => 1.0, "f1" => 0.5, "f3" => true, "f4" => false, "f5" => 1);
        $this->hashIterator2 = new ArrayIterator($this->hash2);
        $this->array2 = array_values($this->hash2);
        $this->arrayIterator2 = new ArrayIterator($this->array2);

        $this->hash3 = array("f0" => array(), "f1" => new stdClass(), "f2" => null, "f3" => "foo");
        $this->hashIterator3 = new ArrayIterator($this->hash3);
        $this->array3 = array_values($this->hash3);
        $this->arrayIterator3 = new ArrayIterator($this->array3);
    }

    function test()
    {
        $this->assertSame(5, average($this->hash));
        $this->assertSame(5, average($this->hashIterator));
        $this->assertSame(5, average($this->array));
        $this->assertSame(5, average($this->arrayIterator));

        $this->assertEquals(0.833333333, average($this->hash2), null, 0.001);
        $this->assertEquals(0.833333333, average($this->hashIterator2), null, 0.001);
        $this->assertEquals(0.833333333, average($this->array2), null, 0.001);
        $this->assertEquals(0.833333333, average($this->arrayIterator2), null, 0.001);

        $this->assertNull(average($this->hash3));
        $this->assertNull(average($this->hashIterator3));
        $this->assertNull(average($this->array3));
        $this->assertNull(average($this->arrayIterator3));
    }

    function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\map() expects parameter 1 to be array or instance of Traversable');
        map('invalidCollection', 'strlen');
    }
}
