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

use function Functional\reject;

class RejectTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->list = ['value', 'wrong', 'value'];
        $this->listIterator = new ArrayIterator($this->list);
        $this->hash = ['k1' => 'value', 'k2' => 'wrong', 'k3' => 'value'];
        $this->hashIterator = new ArrayIterator($this->hash);
    }

    public function test()
    {
        $fn = function ($v, $k, $collection) {
            InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
            return $v == 'wrong' && strlen($k) > 0;
        };
        $this->assertSame([0 => 'value', 2 => 'value'], reject($this->list, $fn));
        $this->assertSame([0 => 'value', 2 => 'value'], reject($this->listIterator, $fn));
        $this->assertSame(['k1' => 'value', 'k3' => 'value'], reject($this->hash, $fn));
        $this->assertSame(['k1' => 'value', 'k3' => 'value'], reject($this->hashIterator, $fn));
    }

    public function testPassNonCallable()
    {
        $this->expectArgumentError("Argument 2 passed to Functional\\reject() must be callable");
        reject($this->list, 'undefinedFunction');
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\reject() expects parameter 1 to be array or instance of Traversable');
        reject('invalidCollection', 'strlen');
    }

    public function testExceptionIsThrownInArray()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        reject($this->list, [$this, 'exception']);
    }

    public function testExceptionIsThrownInHash()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        reject($this->hash, [$this, 'exception']);
    }

    public function testExceptionIsThrownInIterator()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        reject($this->listIterator, [$this, 'exception']);
    }

    public function testExceptionIsThrownInHashIterator()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        reject($this->hashIterator, [$this, 'exception']);
    }
}
