<?php
/**
 * Copyright (C) 2015 by Dan Revel <dan@nopolabs.com>
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

class FlatMapTest extends AbstractTestCase
{
    function setUp()
    {
        parent::setUp(array('Functional\\flat_map'));
        $this->array = array('v1', 'v2', 'v3');
        $this->iterator = new ArrayIterator($this->array);
        $this->hash = array('k1' => 'v1', 'k2' => 'v2', 'k3' => 'v3');
        $this->hashIterator = new ArrayIterator($this->hash);
    }

    function test()
    {
        $fn = function($v, $k, $collection) {
            Exceptions\InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
            if ($v === 'v3') {
                return array(); // flat_map will drop an empty array
            }
            $nestedArray = str_split($v);
            return array($k, $v, $nestedArray); // flat_map will flatten one level of nesting
        };
        $this->assertEquals(array('0','v1',array('v','1'),'1','v2',array('v','2')), flat_map($this->array, $fn));
        $this->assertEquals(array('0','v1',array('v','1'),'1','v2',array('v','2')), flat_map($this->iterator, $fn));
        $this->assertEquals(array('k1','v1',array('v','1'),'k2','v2',array('v','2')), flat_map($this->hash, $fn));
        $this->assertEquals(array('k1','v1',array('v','1'),'k2','v2',array('v','2')), flat_map($this->hashIterator, $fn));
    }

    function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\flat_map() expects parameter 1 to be array or instance of Traversable');
        flat_map('invalidCollection', 'strlen');
    }

    function testPassNonCallable()
    {
        $this->expectArgumentError("Functional\\flat_map() expects parameter 2 to be a valid callback, function 'undefinedFunction' not found or invalid function name");
        flat_map($this->array, 'undefinedFunction');
    }
}