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

use function Functional\last;

class LastTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->list = ['first', 'second', 'third', 'fourth'];
        $this->listIterator = new ArrayIterator($this->list);
        $this->badArray = ['foo', 'bar', 'baz'];
        $this->badIterator = new ArrayIterator($this->badArray);
    }

    public function test()
    {
        $fn = function ($v, $k, $collection) {
            InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);
            return ($v == 'first' && $k == 0) || ($v == 'third' && $k == 2);
        };

        $this->assertSame('third', last($this->list, $fn));
        $this->assertSame('third', last($this->listIterator, $fn));
        $this->assertNull(last($this->badArray, $fn));
        $this->assertNull(last($this->badIterator, $fn));
    }

    public function testWithoutCallback()
    {
        $this->assertSame('fourth', last($this->list));
        $this->assertSame('fourth', last($this->list, null));
        $this->assertSame('fourth', last($this->listIterator));
        $this->assertSame('fourth', last($this->listIterator, null));
    }

    public function testPassNonCallable()
    {
        $this->expectArgumentError('Argument 2 passed to Functional\last() must be callable');
        last($this->list, 'undefinedFunction');
    }

    public function testExceptionIsThrownInArray()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        last($this->list, [$this, 'exception']);
    }

    public function testExceptionIsThrownInCollection()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        last($this->listIterator, [$this, 'exception']);
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\last() expects parameter 1 to be array or instance of Traversable');
        last('invalidCollection', 'strlen');
    }
}
