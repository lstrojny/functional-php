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

use function Functional\map;

class MapTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->list = ['value', 'value'];
        $this->listIterator = new ArrayIterator($this->list);
        $this->hash = ['k1' => 'val1', 'k2' => 'val2'];
        $this->hashIterator = new ArrayIterator($this->hash);
    }

    public function test()
    {
        $fn = function ($v, $k, $collection) {
            InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
            return $k . $v;
        };
        $this->assertSame(['0value', '1value'], map($this->list, $fn));
        $this->assertSame(['0value', '1value'], map($this->listIterator, $fn));
        $this->assertSame(['k1' => 'k1val1', 'k2' => 'k2val2'], map($this->hash, $fn));
        $this->assertSame(['k1' => 'k1val1', 'k2' => 'k2val2'], map($this->hashIterator, $fn));
    }

    public function testExceptionIsThrownInArray()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        map($this->list, [$this, 'exception']);
    }

    public function testExceptionIsThrownInHash()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        map($this->hash, [$this, 'exception']);
    }

    public function testExceptionIsThrownInIterator()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        map($this->listIterator, [$this, 'exception']);
    }

    public function testExceptionIsThrownInHashIterator()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        map($this->hashIterator, [$this, 'exception']);
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\map() expects parameter 1 to be array or instance of Traversable');
        map('invalidCollection', 'strlen');
    }

    public function testPassNonCallable()
    {
        $this->expectArgumentError("Argument 2 passed to Functional\map() must be callable");
        map($this->list, 'undefinedFunction');
    }
}
