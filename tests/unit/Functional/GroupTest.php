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

class GroupTest extends AbstractTestCase
{
    function setUp()
    {
        parent::setUp();
        $this->array = array('value1', 'value2', 'value3');
        $this->iterator = new ArrayIterator($this->array);
        $this->keyedArray = array('k1' => 'val1', 'k2' => 'val2', 'k3' => 'val3');
        $this->keyedIterator = new ArrayIterator($this->keyedArray);
    }

    function test()
    {
        $fn = function($v, $k, $collection) {
            Exceptions\InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
            return (is_int($k) ? ($k % 2 == 0) : ($v[3] % 2 == 0)) ? 'foo' : 'bar';
        };
        $this->assertSame(array('foo' => array('value1', 'value3'), 'bar' => array('value2')), group($this->array, $fn));
        $this->assertSame(array('foo' => array('value1', 'value3'), 'bar' => array('value2')), group($this->iterator, $fn));
        $this->assertSame(array('bar' => array('val1', 'val3'), 'foo' => array('val2')), group($this->keyedArray, $fn));
        $this->assertSame(array('bar' => array('val1', 'val3'), 'foo' => array('val2')), group($this->keyedIterator, $fn));
    }

    function testExceptionIsThrownWhenCallbacksReturnsInvalidKey()
    {
        $array = array('v1', 'v2', 'v3', 'v4', 'v5', 'v6');
        $keyMap = array(true, 1, -1, 2.1, 'str', null);
        $fn = function($v, $k, $collection) use(&$keyMap) {
            return $keyMap[$k];
        };
        $result = array(
            1     => array('v1', 'v2'),
            -1    => array('v3'),
            2     => array('v4'),
            'str' => array('v5'),
            null  => array('v6')
        );
        $this->assertSame($result, group($array, $fn));
        $this->assertSame($result, group(new ArrayIterator($array), $fn));


        $invalidTypes = array(
                          'resource' => stream_context_create(),
                          'object'   => new \stdClass(),
                          'array'    => array()
                        );

        foreach ($invalidTypes as $type => $value) {
            $keyMap = array($value);
            try {
                group(array('v1'), $fn);
            } catch (\Exception $e) {
                $this->assertSame(
                    sprintf(
                        'Functional\group() callback returned invalid array key of type "%s". Expected NULL, string, integer, double or boolean',
                        $type
                    ),
                    $e->getMessage()
                );
            }
        }
    }

    function testExceptionIsThrownInArray()
    {
        $this->setExpectedException('Exception', 'Callback exception');
        group($this->array, array($this, 'exception'));
    }

    function testExceptionIsThrownInKeyedArray()
    {
        $this->setExpectedException('Exception', 'Callback exception');
        group($this->keyedArray, array($this, 'exception'));
    }

    function testExceptionIsThrownInIterator()
    {
        $this->setExpectedException('Exception', 'Callback exception');
        group($this->iterator, array($this, 'exception'));
    }

    function testExceptionIsThrownInKeyedIterator()
    {
        $this->setExpectedException('Exception', 'Callback exception');
        group($this->keyedIterator, array($this, 'exception'));
    }

    function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\group() expects parameter 1 to be array or instance of Traversable');
        group('invalidCollection', 'strlen');
    }

    function testPassNonCallable()
    {
        $this->expectArgumentError("Functional\group() expects parameter 2 to be a valid callback, function 'undefinedFunction' not found or invalid function name");
        group($this->array, 'undefinedFunction');
    }
}
