<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use ArrayIterator;
use Functional\Exceptions\InvalidArgumentException;

use function Functional\partition;

class PartitionTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->list = ['value1', 'value2', 'value3'];
        $this->listIterator = new ArrayIterator($this->list);
        $this->hash = ['k1' => 'val1', 'k2' => 'val2', 'k3' => 'val3'];
        $this->hashIterator = new ArrayIterator($this->hash);
    }

    public function test()
    {
        $fn = function ($v, $k, $collection) {
            InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
            return is_int($k) ? ($k % 2 == 0) : ($v[3] % 2 == 0);
        };
        $this->assertSame([[0 => 'value1', 2 => 'value3'], [1 => 'value2']], partition($this->list, $fn));
        $this->assertSame([[0 => 'value1', 2 => 'value3'], [1 => 'value2']], partition($this->listIterator, $fn));
        $this->assertSame([['k2' => 'val2'], ['k1' => 'val1', 'k3' => 'val3']], partition($this->hash, $fn));
        $this->assertSame([['k2' => 'val2'], ['k1' => 'val1', 'k3' => 'val3']], partition($this->hashIterator, $fn));
    }

    public function testMultiFn()
    {
        $fn1 = function ($v, $k, $collection) {
            InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
            return is_int($k) ? ($k === 1) : ($v[3] === '2');
        };

        $fn2 = function ($v, $k, $collection) {
            InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
            return is_int($k) ? ($k === 2) : ($v[3] === '3');
        };

        $this->assertSame([[1 => 'value2'], [2 => 'value3'], [0 => 'value1']], partition($this->list, $fn1, $fn2));
        $this->assertSame([[1 => 'value2'], [2 => 'value3'], [0 => 'value1']], partition($this->listIterator, $fn1, $fn2));
        $this->assertSame([['k2' => 'val2'], ['k3' => 'val3'], ['k1' => 'val1']], partition($this->hash, $fn1, $fn2));
        $this->assertSame([['k2' => 'val2'], ['k3' => 'val3'], ['k1' => 'val1']], partition($this->hashIterator, $fn1, $fn2));
    }

    public function testExceptionIsThrownInArray()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        partition($this->list, [$this, 'exception']);
    }

    public function testExceptionIsThrownInHash()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        partition($this->hash, [$this, 'exception']);
    }

    public function testExceptionIsThrownInIterator()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        partition($this->listIterator, [$this, 'exception']);
    }

    public function testExceptionIsThrownInHashIterator()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        partition($this->hashIterator, [$this, 'exception']);
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\partition() expects parameter 1 to be array or instance of Traversable');
        partition('invalidCollection', 'strlen');
    }

    public function testPassNonCallable()
    {
        $this->expectArgumentError("Argument 2 passed to Functional\partition() must be callable");
        partition($this->list, 'undefinedFunction');
    }
}
