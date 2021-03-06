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

use function Functional\map;

class MapTest extends AbstractTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->list = ['value', 'value'];
        $this->listIterator = new ArrayIterator($this->list);
        $this->hash = ['k1' => 'val1', 'k2' => 'val2'];
        $this->hashIterator = new ArrayIterator($this->hash);
    }

    public function test(): void
    {
        $fn = function ($v, $k, $collection) {
            InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
            return $k . $v;
        };
        self::assertSame(['0value', '1value'], map($this->list, $fn));
        self::assertSame(['0value', '1value'], map($this->listIterator, $fn));
        self::assertSame(['k1' => 'k1val1', 'k2' => 'k2val2'], map($this->hash, $fn));
        self::assertSame(['k1' => 'k1val1', 'k2' => 'k2val2'], map($this->hashIterator, $fn));
    }

    public function testExceptionIsThrownInArray(): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        map($this->list, [$this, 'exception']);
    }

    public function testExceptionIsThrownInHash(): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        map($this->hash, [$this, 'exception']);
    }

    public function testExceptionIsThrownInIterator(): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        map($this->listIterator, [$this, 'exception']);
    }

    public function testExceptionIsThrownInHashIterator(): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        map($this->hashIterator, [$this, 'exception']);
    }

    public function testPassNoCollection(): void
    {
        $this->expectArgumentError('Functional\map() expects parameter 1 to be array or instance of Traversable');
        map('invalidCollection', 'strlen');
    }

    public function testPassNonCallable(): void
    {
        $this->expectCallableArgumentError('Functional\map', 2);
        map($this->list, 'undefinedFunction');
    }
}
