<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\first_index_of;

class FirstIndexOfTest extends AbstractTestCase
{
    use IndexesTrait;

    public function test()
    {
        $this->assertSame(0, first_index_of($this->list, 'value1'));
        $this->assertSame(0, first_index_of($this->listIterator, 'value1'));
        $this->assertSame(1, first_index_of($this->list, 'value'));
        $this->assertSame(1, first_index_of($this->listIterator, 'value'));
        $this->assertSame(3, first_index_of($this->list, 'value2'));
        $this->assertSame(3, first_index_of($this->listIterator, 'value2'));
        $this->assertSame('k1', first_index_of($this->hash, 'val1'));
        $this->assertSame('k1', first_index_of($this->hashIterator, 'val1'));
        $this->assertSame('k2', first_index_of($this->hash, 'val2'));
        $this->assertSame('k2', first_index_of($this->hashIterator, 'val2'));
        $this->assertSame('k4', first_index_of($this->hash, 'val3'));
        $this->assertSame('k4', first_index_of($this->hashIterator, 'val3'));
    }

    public function testIfValueCouldNotBeFoundFalseIsReturned()
    {
        $this->assertFalse(first_index_of($this->list, 'invalidValue'));
        $this->assertFalse(first_index_of($this->listIterator, 'invalidValue'));
        $this->assertFalse(first_index_of($this->hash, 'invalidValue'));
        $this->assertFalse(first_index_of($this->hashIterator, 'invalidValue'));
    }

    public function testPassCallback()
    {
        $this->assertSame(
            0,
            first_index_of($this->list, function ($element) {
                return $element;
            })
        );
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\first_index_of() expects parameter 1 to be array or instance of Traversable');
        first_index_of('invalidCollection', 'idx');
    }
}
