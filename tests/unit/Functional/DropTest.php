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

class DropTest extends AbstractTestCase
{
    function setUp()
    {
        parent::setUp(array('Functional\drop_first', 'Functional\drop_last'));
        $this->array = array('value1', 'value2', 'value3', 'value4');
        $this->iterator = new ArrayIterator($this->array);
        $this->hash = array('k1' => 'val1', 'k2' => 'val2', 'k3' => 'val3', 'k4' => 'val4');
        $this->hashIterator = new ArrayIterator($this->hash);
    }

    function test()
    {
        $fn = function($v, $k, $collection) {
            Exceptions\InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
            $return = is_int($k) ? ($k != 2) : ($v[3] != 3);
            return $return;
        };
        $this->assertSame(array(0 => 'value1', 1 => 'value2'), drop_last($this->array, $fn));
        $this->assertSame(array(2 => 'value3', 3 => 'value4'), drop_first($this->array, $fn));
        $this->assertSame(array(2 => 'value3', 3 => 'value4'), drop_first($this->iterator, $fn));
        $this->assertSame(array(0 => 'value1', 1 => 'value2'), drop_last($this->iterator, $fn));
        $this->assertSame(array('k3' => 'val3', 'k4' => 'val4'), drop_first($this->hash, $fn));
        $this->assertSame(array('k1' => 'val1', 'k2' => 'val2'), drop_last($this->hash, $fn));
        $this->assertSame(array('k3' => 'val3', 'k4' => 'val4'), drop_first($this->hashIterator, $fn));
        $this->assertSame(array('k1' => 'val1', 'k2' => 'val2'), drop_last($this->hashIterator, $fn));
    }

    public static function getFunctions()
    {
        return array(array('Functional\drop_first'), array('Functional\drop_last'));
    }

    /** @dataProvider getFunctions */
    function testExceptionIsThrownInArray($fn)
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        $fn($this->array, array($this, 'exception'));
    }

    /** @dataProvider getFunctions */
    function testExceptionIsThrownInHash($fn)
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        $fn($this->hash, array($this, 'exception'));
    }

    /** @dataProvider getFunctions */
    function testExceptionIsThrownInIterator($fn)
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        $fn($this->iterator, array($this, 'exception'));
    }

    /** @dataProvider getFunctions */
    function testExceptionIsThrownInHashIterator($fn)
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        $fn($this->hashIterator, array($this, 'exception'));
    }

    /** @dataProvider getFunctions */
    function testPassNoCollection($fn)
    {
        $this->expectArgumentError($fn . '() expects parameter 1 to be array or instance of Traversable');
        $fn('invalidCollection', 'strlen');
    }

    /** @dataProvider getFunctions */
    function testPassNonCallable($fn)
    {
        $this->expectArgumentError($fn . "() expects parameter 2 to be a valid callback, function 'undefinedFunction' not found or invalid function name");
        $fn($this->array, 'undefinedFunction');
    }
}
