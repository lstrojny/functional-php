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

use function Functional\some;

class SomeTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->goodArray = ['value', 'wrong'];
        $this->goodIterator = new ArrayIterator($this->goodArray);
        $this->badArray = ['wrong', 'wrong', 'wrong'];
        $this->badIterator = new ArrayIterator($this->badArray);
    }

    public function test()
    {
        $this->assertTrue(some($this->goodArray, [$this, 'functionalCallback']));
        $this->assertTrue(some($this->goodIterator, [$this, 'functionalCallback']));
        $this->assertFalse(some($this->badArray, [$this, 'functionalCallback']));
        $this->assertFalse(some($this->badIterator, [$this, 'functionalCallback']));
    }

    public function testPassNonCallable()
    {
        $this->expectArgumentError("Argument 2 passed to Functional\some() must be callable");
        some($this->goodArray, 'undefinedFunction');
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\some() expects parameter 1 to be array or instance of Traversable');
        some('invalidCollection', 'strlen');
    }

    public function testExceptionThrownInArray()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        some($this->goodArray, [$this, 'exception']);
    }

    public function testExceptionThrownInCollection()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        some($this->goodIterator, [$this, 'exception']);
    }

    public function functionalCallback($value, $key, $collection)
    {
        InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
        return $value == 'value' && $key === 0;
    }
}
