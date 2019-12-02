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

use function Functional\none;

class NoneTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->goodArray = ['value', 'value', 'value'];
        $this->goodIterator = new ArrayIterator($this->goodArray);
        $this->badArray = ['value', 'value', 'foo'];
        $this->badIterator = new ArrayIterator($this->badArray);
    }

    public function test()
    {
        $this->assertTrue(none($this->goodArray, [$this, 'functionalCallback']));
        $this->assertTrue(none($this->goodIterator, [$this, 'functionalCallback']));
        $this->assertFalse(none($this->badArray, [$this, 'functionalCallback']));
        $this->assertFalse(none($this->badIterator, [$this, 'functionalCallback']));
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\none() expects parameter 1 to be array or instance of Traversable');
        none('invalidCollection', 'strlen');
    }

    public function testPassNonCallable()
    {
        $this->expectArgumentError("Argument 2 passed to Functional\\none() must be callable");
        none($this->goodArray, 'undefinedFunction');
    }

    public function testExceptionIsThrownInArray()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        none($this->goodArray, [$this, 'exception']);
    }

    public function testExceptionIsThrownInIterator()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        none($this->goodIterator, [$this, 'exception']);
    }

    public function functionalCallback($value, $key, $collection)
    {
        InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
        return $value != 'value' && strlen($key) > 0;
    }
}
