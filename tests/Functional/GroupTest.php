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

use ArrayIterator;
use Functional\Exceptions\InvalidArgumentException;
use function Functional\group;

class GroupTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->list = ['value1', 'value2', 'value3', 'value4'];
        $this->listIterator = new ArrayIterator($this->list);
        $this->hash = ['k1' => 'val1', 'k2' => 'val2', 'k3' => 'val3'];
        $this->hashIterator = new ArrayIterator($this->hash);
    }

    public function test()
    {
        $fn = function($v, $k, $collection) {
            InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
            return (is_int($k) ? ($k % 2 == 0) : ($v[3] % 2 == 0)) ? 'foo' : '';
        };
        $this->assertSame(['foo' => [0 => 'value1', 2 => 'value3'], '' => [1 => 'value2', 3 => 'value4']], group($this->list, $fn));
        $this->assertSame(['foo' => [0 => 'value1', 2 => 'value3'], '' => [1 => 'value2', 3 => 'value4']], group($this->listIterator, $fn));
        $this->assertSame(['' => ['k1' => 'val1', 'k3' => 'val3'], 'foo' => ['k2' => 'val2']], group($this->hash, $fn));
        $this->assertSame(['' => ['k1' => 'val1', 'k3' => 'val3'], 'foo' => ['k2' => 'val2']], group($this->hashIterator, $fn));
    }

    public function testExceptionIsThrownWhenCallbacksReturnsInvalidKey()
    {
        $array = ['v1', 'v2', 'v3', 'v4', 'v5', 'v6'];
        $keyMap = [true, 1, -1, 2.1, 'str', null];
        $fn = function($v, $k, $collection) use(&$keyMap) {
            return $keyMap[$k];
        };
        $result = [
            1     => [0 => 'v1', 1 => 'v2'],
            -1    => [2 => 'v3'],
            2     => [3 => 'v4'],
            'str' => [4 => 'v5'],
            null  => [5 => 'v6']
        ];
        $this->assertSame($result, group($array, $fn));
        $this->assertSame($result, group(new ArrayIterator($array), $fn));


        $invalidTypes = [
                          'resource' => stream_context_create(),
                          'object'   => new \stdClass(),
                          'array'    => []
        ];

        foreach ($invalidTypes as $type => $value) {
            $keyMap = [$value];
            try {
                group(['v1'], $fn);
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

    public function testExceptionIsThrownInArray()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        group($this->list, [$this, 'exception']);
    }

    public function testExceptionIsThrownInHash()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        group($this->hash, [$this, 'exception']);
    }

    public function testExceptionIsThrownInIterator()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        group($this->listIterator, [$this, 'exception']);
    }

    public function testExceptionIsThrownInHashIterator()
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        group($this->hashIterator, [$this, 'exception']);
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\group() expects parameter 1 to be array or instance of Traversable');
        group('invalidCollection', 'strlen');
    }

    public function testPassNonCallable()
    {
        $this->expectArgumentError("Argument 2 passed to Functional\group() must be callable");
        group($this->list, 'undefinedFunction');
    }
}
