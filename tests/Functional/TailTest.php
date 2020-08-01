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

use function Functional\tail;

class TailTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->list = [1, 2, 3, 4];
        $this->listIterator = new ArrayIterator($this->list);
        $this->badArray = ['foo', 'bar', 'baz'];
        $this->badIterator = new ArrayIterator($this->badArray);
    }

    public function test()
    {
        $fn = function ($v, $k, $collection) {
            InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);
            return $v > 2;
        };

        $this->assertSame([2 => 3, 3 => 4], tail($this->list, $fn));
        $this->assertSame([2 => 3, 3 => 4], tail($this->listIterator, $fn));
        $this->assertSame([], tail($this->badArray, $fn));
        $this->assertSame([], tail($this->badIterator, $fn));
    }

    public function testWithoutCallback()
    {
        $this->assertSame([1 => 2, 2 => 3, 3 => 4], tail($this->list));
        $this->assertSame([1 => 2, 2 => 3, 3 => 4], tail($this->list, null));
        $this->assertSame([1 => 2, 2 => 3, 3 => 4], tail($this->listIterator));
        $this->assertSame([1 => 2, 2 => 3, 3 => 4], tail($this->listIterator, null));
        $this->assertSame([1 => 'bar', 2 => 'baz'], tail($this->badArray));
        $this->assertSame([1 => 'bar', 2 => 'baz'], tail($this->badArray, null));
        $this->assertSame([1 => 'bar', 2 => 'baz'], tail($this->badIterator));
        $this->assertSame([1 => 'bar', 2 => 'baz'], tail($this->badIterator, null));
    }

    public function testPassNonCallable()
    {
        $this->expectArgumentError('Argument 2 passed to Functional\tail() must be callable');
        tail($this->list, 'undefinedFunction');
    }

    public function testExceptionIsThrownInArray()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        tail($this->list, [$this, 'exception']);
    }

    public function testExceptionIsThrownInCollection()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        tail($this->listIterator, [$this, 'exception']);
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\tail() expects parameter 1 to be array or instance of Traversable');
        tail('invalidCollection', 'strlen');
    }
}
