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

use function Functional\every;

class EveryTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->goodArray = ['value', 'value', 'value'];
        $this->goodIterator = new ArrayIterator($this->goodArray);
        $this->badArray = ['value', 'nope', 'value'];
        $this->badIterator = new ArrayIterator($this->badArray);
    }

    public function test()
    {
        $this->assertTrue(every($this->goodArray, [$this, 'functionalCallback']));
        $this->assertTrue(every($this->goodIterator, [$this, 'functionalCallback']));
        $this->assertFalse(every($this->badArray, [$this, 'functionalCallback']));
        $this->assertFalse(every($this->badIterator, [$this, 'functionalCallback']));
    }

    public function testPassNonCallable()
    {
        $this->expectArgumentError("Argument 2 passed to Functional\\every() must be callable");
        every($this->goodArray, 'undefinedFunction');
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\every() expects parameter 1 to be array or instance of Traversable');
        every('invalidCollection', 'strlen');
    }

    public function testExceptionIsThrownInArray()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        every($this->goodArray, [$this, 'exception']);
    }

    public function testExceptionIsThrownInCollection()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        every($this->goodIterator, [$this, 'exception']);
    }

    public function functionalCallback($value, $key, $collection)
    {
        InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);

        return $value == 'value' && is_numeric($key);
    }
}
