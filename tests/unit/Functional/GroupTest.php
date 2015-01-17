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

class GroupTest extends AbstractTestCase
{
    function setUp()
    {
        parent::setUp();
        $this->array = array('value1', 'value2', 'value3', 'value4');
        $this->iterator = new ArrayIterator($this->array);
        $this->hash = array('k1' => 'val1', 'k2' => 'val2', 'k3' => 'val3');
        $this->hashIterator = new ArrayIterator($this->hash);
    }

    function test()
    {
        $fn = function($v, $k, $collection) {
            Exceptions\InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
            return (is_int($k) ? ($k % 2 == 0) : ($v[3] % 2 == 0)) ? 'foo' : '';
        };
        $this->assertSame(array('foo' => array(0 => 'value1', 2 => 'value3'), '' => array(1 => 'value2', 3 => 'value4')), group($this->array, $fn));
        $this->assertSame(array('foo' => array(0 => 'value1', 2 => 'value3'), '' => array(1 => 'value2', 3 => 'value4')), group($this->iterator, $fn));
        $this->assertSame(array('' => array('k1' => 'val1', 'k3' => 'val3'), 'foo' => array('k2' => 'val2')), group($this->hash, $fn));
        $this->assertSame(array('' => array('k1' => 'val1', 'k3' => 'val3'), 'foo' => array('k2' => 'val2')), group($this->hashIterator, $fn));
    }

    function testExceptionIsThrownWhenCallbacksReturnsInvalidKey()
    {
        $array = array('v1', 'v2', 'v3', 'v4', 'v5', 'v6');
        $keyMap = array(true, 1, -1, 2.1, 'str', null);
        $fn = function($v, $k, $collection) use(&$keyMap) {
            return $keyMap[$k];
        };
        $result = array(
            1     => array(0 => 'v1', 1 => 'v2'),
            -1    => array(2 => 'v3'),
            2     => array(3 => 'v4'),
            'str' => array(4 => 'v5'),
            null  => array(5 => 'v6')
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
                $this->fail(sprintf('Error expected for array key type "%s"', $type));
            } catch (\Exception $e) {
                $this->assertSame(
                    sprintf(
                        'Functional\group(): callback returned invalid array key of type "%s". Expected NULL, string, integer, double or boolean',
                        $type
                    ),
                    $e->getMessage()
                );
            }
        }
    }

    function testExceptionIsThrownInArray()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        group($this->array, array($this, 'exception'));
    }

    function testExceptionIsThrownInHash()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        group($this->hash, array($this, 'exception'));
    }

    function testExceptionIsThrownInIterator()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        group($this->iterator, array($this, 'exception'));
    }

    function testExceptionIsThrownInHashIterator()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        group($this->hashIterator, array($this, 'exception'));
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
