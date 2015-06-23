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

use ArrayObject;
use function Functional\pick;

class PickTest extends AbstractTestCase
{
    private $array_1;

    public function setUp()
    {
        parent::setUp();
        $this->array_1 = [
            'one' => '1',
            'two' => '2',
            3 => '3',
            'null-index' => null,
            'zero-index' => 0,
            'zero-string-index' => '0',
            'false-index' => false
        ];
    }

    public function test()
    {
        $this->assertSame('2', pick($this->array_1, 'two'));
        $this->assertSame(
            null,
            pick($this->array_1, 'non-existing-index'),
            'Non-existing index, should return null'
        );
        $this->assertSame('3', pick($this->array_1, 3));
        $this->assertSame(0, pick($this->array_1, 'zero-index', ':)'));
        $this->assertSame('default', pick($this->array_1, 'non-existing-index', 'default'));
    }

    public function testInvalidCollectionShouldThrowException()
    {
        $this->setExpectedException('Functional\Exceptions\InvalidArgumentException');

        pick(null, '');
    }

    public function testInvalidCallbackShouldThrowException()
    {
        $this->expectArgumentError('Argument 4 passed to Functional\pick() must be callable');
        pick($this->array_1, '', null, 'not-a-callback');
    }

    public function testDefaultValue()
    {
        $this->assertSame(
            5,
            pick($this->array_1, 'dummy', 5),
            'Index does not exist, should return default value'
        );

        $this->assertSame(
            '1',
            pick($this->array_1, 'one', 5),
            'Index does exists, should return the corresponding value'
        );
    }

    public function testCustomCallback()
    {
        //custom callback to check for false condition
        //false - index does not exist or value is 0
        //true - any other values other than 0, including false, 'false', null, and so on
        $customCallback = function ($collection, $key) {
            if (!isset($collection[$key]) || 0 === $collection[$key]) {
                return false;
            } else {
                return true;
            }
        };

        $this->assertSame(':)', pick($this->array_1, 'non-existing-index', ':)'));
        $this->assertSame(':)', pick($this->array_1, 'zero-index', ':)', $customCallback));
        $this->assertSame('0', pick($this->array_1, 'zero-string-index', ':)', $customCallback));
        $this->assertSame(false, pick($this->array_1, 'false-index', ':)', $customCallback));
    }

    public function testArrayAccess()
    {
        $object = new ArrayObject();

        $object['test'] = 5;

        $this->assertSame(5, pick($object, 'test'), 'Key exists');
        $this->assertSame(10, pick($object, 'dummy', 10), 'Key does not exists');
    }
}
