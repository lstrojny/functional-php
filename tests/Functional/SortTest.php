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
use Functional as F;
use Functional\Exceptions\InvalidArgumentException;

use function Functional\sort;

class SortTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->list = ['cat', 'bear', 'aardvark'];
        $this->listIterator = new ArrayIterator($this->list);
        $this->hash = ['c' => 'cat', 'b' => 'bear', 'a' => 'aardvark'];
        $this->hashIterator = new ArrayIterator($this->hash);
        $this->sortCallback = function ($left, $right, $collection) {
            InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
            return strcmp($left, $right);
        };
    }

    public function testPreserveKeys()
    {
        $this->assertSame([2 => 'aardvark', 1 => 'bear', 0 => 'cat'], F\sort($this->list, $this->sortCallback, true));
        $this->assertSame([2 => 'aardvark', 1 => 'bear', 0 => 'cat'], F\sort($this->listIterator, $this->sortCallback, true));
        $this->assertSame(['a' => 'aardvark', 'b' => 'bear', 'c' => 'cat'], F\sort($this->hash, $this->sortCallback, true));
        $this->assertSame(['a' => 'aardvark', 'b' => 'bear', 'c' => 'cat'], F\sort($this->hashIterator, $this->sortCallback, true));
    }

    public function testWithoutPreserveKeys()
    {
        $this->assertSame([0 => 'aardvark', 1 => 'bear', 2 => 'cat'], F\sort($this->list, $this->sortCallback, false));
        $this->assertSame([0 => 'aardvark', 1 => 'bear', 2 => 'cat'], F\sort($this->listIterator, $this->sortCallback, false));
        $this->assertSame([0 => 'aardvark', 1 => 'bear', 2 => 'cat'], F\sort($this->hash, $this->sortCallback, false));
        $this->assertSame([0 => 'aardvark', 1 => 'bear', 2 => 'cat'], F\sort($this->hashIterator, $this->sortCallback, false));
    }

    public function testPassNonCallable()
    {
        $this->expectArgumentError("Argument 2 passed to Functional\sort() must be callable");
        F\sort($this->list, 'undefinedFunction');
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('sort() expects parameter 1 to be array or instance of Traversable');
        F\sort('invalidCollection', 'strlen');
    }

    public function testExceptionIsThrownInArray()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        F\sort($this->list, [$this, 'exception']);
    }

    public function testExceptionIsThrownInHash()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        F\sort($this->hash, [$this, 'exception']);
    }

    public function testExceptionIsThrownInIterator()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        F\sort($this->listIterator, [$this, 'exception']);
    }

    public function testExceptionIsThrownInHashIterator()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        F\sort($this->hashIterator, [$this, 'exception']);
    }
}
