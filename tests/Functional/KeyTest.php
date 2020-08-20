<?php
/**
 * Copyright (C) 2011-2017 by Lars Strojny <lstrojny@php.net>
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

use ArrayIterator;
use function Functional\key;

class KeyTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->list = ['value1', 'value2', 'value3', 'value4'];
        $this->listIterator = new ArrayIterator($this->list);
        $this->hash = ['k1' => 'val1', 'k2' => 'val2', 'k3' => 'val3'];
        $this->hashIterator = new ArrayIterator($this->hash);
    }

    public function testKeyByIndex()
    {
        $result = key(['value 1', 'value 3', 'value 2'], function($_, $idx) {
             return $idx;
        });
        $expected = [
            0 => 'value 1',
            1 => 'value 3',
            2 => 'value 2'
        ];
        $this->assertSame($expected, $result);
    }

    public function testKeyByValue()
    {
        $collection = [[
            'first name' => 'John',
            'last name'  => 'Doe'
        ], [
            'first name' => 'Jane',
            'last name'  => 'Smith'
        ]];
        $expected = [
            'John' => [
                'first name' => 'John',
                'last name'  => 'Doe'
            ],
            'Jane' => [
                'first name' => 'Jane',
                'last name'  => 'Smith'
            ]
        ];
        $result = key($collection, function($item) {
            return $item['first name'];
        });

        $this->assertSame($expected, $result);
    }

    public function testKeyPreservesLastItem()
    {
        $collection = [[
            'key' => 'key',
            'value'  => 'value 1'
        ], [
            'key' => 'key',
            'value'  => 'value 2'
        ], [
            'key' => 'key',
            'value'  => 'value 3'
        ]];
        $expected = [
            'key' => [
                'key' => 'key',
                'value' => 'value 3'
            ]
        ];
        $result = key($collection, function() {
            return 'key';
        });

        $this->assertSame($expected, $result);
    }

    /**
     * @dataProvider invalidKeysDataProvider
     * @param string $key
     * @param string $type
     */
    public function testExceptionIsThrownWhenCallbacksReturnsInvalidKey($key, $type)
    {
        $this->expectException('Functional\Exceptions\InvalidArgumentException');
        $this->expectExceptionMessage("Functional\key(): callback returned invalid array key of type \"$type\"");
        $fn = function() use ($key) {
            return $key;
        };
        key([1], $fn);
    }

    public function invalidKeysDataProvider() {
        return [
            'resource' => [
                'key' => stream_context_create(),
                'type' => 'resource'
            ],
            'object'   => [
                'key' => new \stdClass(),
                'type' => 'object'
            ],
            'array'    => [
                'key' => [],
                'type' => 'array'
            ]
        ];
    }

    public function testExceptionIsThrownInArray()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        key($this->list, [$this, 'exception']);
    }

    public function testExceptionIsThrownInHash()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        key($this->hash, [$this, 'exception']);
    }

    public function testExceptionIsThrownInIterator()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        key($this->listIterator, [$this, 'exception']);
    }

    public function testExceptionIsThrownInHashIterator()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        key($this->hashIterator, [$this, 'exception']);
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\key() expects parameter 1 to be array or instance of Traversable');
        key('invalidCollection', 'strlen');
    }

    public function testPassNonCallable()
    {
        $this->expectArgumentError("Argument 2 passed to Functional\key() must be callable");
        key($this->list, 'undefinedFunction');
    }
}
