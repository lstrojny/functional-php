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

class MaximumTest extends AbstractTestCase
{
    function setUp()
    {
        parent::setUp();
        $this->array = array(1, "foo", 5.1, 5, "5.2", true, false, array(), new stdClass());
        $this->iterator = new ArrayIterator($this->array);
        $this->hash = array(
            'k1' => 1,
            'k2' => '5.2',
            'k3' => 5,
            'k4' => '5.1',
            'k5' => 10.2,
            'k6' => true,
            'k7' => array(),
            'k8' => new stdClass(),
        );
        $this->hashIterator = new ArrayIterator($this->hash);
    }

    function testExtractingMaximumValue()
    {
        $this->assertEquals('5.2', maximum($this->array));
        return;
        $this->assertEquals('5.2', maximum($this->iterator));
        $this->assertEquals(10.2, maximum($this->hash));
        $this->assertEquals(10.2, maximum($this->hashIterator));
    }

    function testSpecialCaseNull()
    {
        $this->assertSame(-1, maximum(array(-1)));
    }

    function testSpecialCaseSameValueDifferentTypes()
    {
        $this->assertSame(1, maximum(array(0, 1, 0.0, 1.0, "0", "1", "0.0", "1.0")));
    }

    function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\maximum() expects parameter 1 to be array or instance of Traversable');
        maximum('invalidCollection');
    }
}
