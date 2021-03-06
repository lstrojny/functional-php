<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use ArrayIterator;
use Functional\Exceptions\InvalidArgumentException;

use function Functional\partition;

class PartitionTest extends AbstractTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->list = ['value1', 'value2', 'value3'];
        $this->listIterator = new ArrayIterator($this->list);
        $this->hash = ['k1' => 'val1', 'k2' => 'val2', 'k3' => 'val3'];
        $this->hashIterator = new ArrayIterator($this->hash);
    }

    public function test(): void
    {
        $fn = function ($v, $k, $collection) {
            InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
            return \is_int($k) ? ($k % 2 == 0) : ($v[3] % 2 == 0);
        };
        self::assertSame([[0 => 'value1', 2 => 'value3'], [1 => 'value2']], partition($this->list, $fn));
        self::assertSame([[0 => 'value1', 2 => 'value3'], [1 => 'value2']], partition($this->listIterator, $fn));
        self::assertSame([['k2' => 'val2'], ['k1' => 'val1', 'k3' => 'val3']], partition($this->hash, $fn));
        self::assertSame([['k2' => 'val2'], ['k1' => 'val1', 'k3' => 'val3']], partition($this->hashIterator, $fn));
    }

    public function testMultiFn(): void
    {
        $fn1 = function ($v, $k, $collection) {
            InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
            return \is_int($k) ? ($k === 1) : ($v[3] === '2');
        };

        $fn2 = function ($v, $k, $collection) {
            InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
            return \is_int($k) ? ($k === 2) : ($v[3] === '3');
        };

        self::assertSame([[1 => 'value2'], [2 => 'value3'], [0 => 'value1']], partition($this->list, $fn1, $fn2));
        self::assertSame([[1 => 'value2'], [2 => 'value3'], [0 => 'value1']], partition($this->listIterator, $fn1, $fn2));
        self::assertSame([['k2' => 'val2'], ['k3' => 'val3'], ['k1' => 'val1']], partition($this->hash, $fn1, $fn2));
        self::assertSame([['k2' => 'val2'], ['k3' => 'val3'], ['k1' => 'val1']], partition($this->hashIterator, $fn1, $fn2));
    }

    public function testExceptionIsThrownInArray(): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        partition($this->list, [$this, 'exception']);
    }

    public function testExceptionIsThrownInHash(): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        partition($this->hash, [$this, 'exception']);
    }

    public function testExceptionIsThrownInIterator(): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        partition($this->listIterator, [$this, 'exception']);
    }

    public function testExceptionIsThrownInHashIterator(): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        partition($this->hashIterator, [$this, 'exception']);
    }

    public function testPassNoCollection(): void
    {
        $this->expectArgumentError('Functional\partition() expects parameter 1 to be array or instance of Traversable');
        partition('invalidCollection', 'strlen');
    }

    public function testPassNonCallable(): void
    {
        $this->expectCallableArgumentError('Functional\partition', 2);
        partition($this->list, 'undefinedFunction');
    }
}
