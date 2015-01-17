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
use BadFunctionCallException;
use stdClass;

class ZipTest extends AbstractTestCase
{
    function setUp()
    {
        parent::setUp();
        $this->array = array('value', 'value');
        $this->iterator = new ArrayIterator($this->array);
        $this->hash = array('k1' => 'val1', 'k2' => 'val2');
        $this->hashIterator = new ArrayIterator($this->hash);
    }

    function testZippingSameSizedArrays()
    {
        $result = array(array('one', 1, -1), array('two', 2, -2), array('three', 3, -3));
        $this->assertSame($result, zip(array('one', 'two', 'three'), array(1, 2, 3), array(-1, -2, -3)));
        $this->assertSame(
            $result,
            zip(
                new ArrayIterator(array('one', 'two', 'three')),
                new ArrayIterator(array(1, 2, 3)),
                new ArrayIterator(array(-1, -2, -3))
            )
        );
    }

    function testZippingDifferentlySizedArrays()
    {
        $result = array(array('one', 1, -1, true), array('two', 2, -2, false), array('three', 3, -3, null));
        $this->assertSame(
            $result,
            zip(array('one', 'two', 'three'), array(1, 2, 3), array(-1, -2, -3), array(true, false))
        );
    }

    function testZippingHashes()
    {
        $result = array(array(1, -1), array(2, -2), array(true, false));
        $this->assertSame(
            $result,
            zip(
                array('foo' => 1, 'bar' => 2, true),
                array('foo' => -1, 'bar' => -2, false, "ignore")
            )
        );
        $this->assertSame(
            $result,
            zip(
                new ArrayIterator(array('foo' => 1, 'bar' => 2, true)),
                new ArrayIterator(array('foo' => -1, 'bar' => -2, false, "ignore"))
            )
        );
    }

    function testZippingWithCallback()
    {
        $result = array('one1-11', 'two2-2', 'three3-3');
        $this->assertSame(
            $result,
            zip(
                array('one', 'two', 'three'),
                array(1, 2, 3),
                array(-1, -2, -3),
                array(true, false),
                function($one, $two, $three, $four) {
                    return $one . $two . $three . $four;
                }
            )
        );
        $this->assertSame(
            $result,
            zip(
                new ArrayIterator(array('one', 'two', 'three')),
                new ArrayIterator(array(1, 2, 3)),
                new ArrayIterator(array(-1, -2, -3)),
                new ArrayIterator(array(true, false)),
                function($one, $two, $three, $four) {
                    return $one . $two . $three . $four;
                }
            )
        );
    }

    function testZippingArraysWithVariousElements()
    {
        $object = new stdClass();
        $resource = stream_context_create();
        $result = array(
            array(array(1), $object, array(2)),
            array(null, 'foo', null),
            array($resource, null, 2)
        );

        $this->assertSame(
            $result,
            zip(
                array(array(1), null, $resource),
                array($object, 'foo', null),
                array(array(2), null, 2)
            )
        );
    }

    function testZipSpecialCases()
    {
        $this->assertSame(array(), zip(array()));
        $this->assertSame(array(), zip(array(), array()));
        $this->assertSame(array(), zip(array(), array(), function() {
            throw new BadFunctionCallException('Should not be called');
        }));
    }

    function testPassNoCollectionAsFirstParam()
    {
        $this->expectArgumentError('Functional\zip() expects parameter 1 to be array or instance of Traversable');
        zip('invalidCollection');
    }

    function testPassNoCollectionAsSecondParam()
    {
        $this->expectArgumentError('Functional\zip() expects parameter 2 to be array or instance of Traversable');
        zip(array(), 'invalidCollection');
    }

    function testExceptionInCallback()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        map(array(null), array($this, 'exception'));
    }
}
