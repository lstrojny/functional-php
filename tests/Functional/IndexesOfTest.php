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

use function Functional\indexes_of;

class IndexesOfTest extends AbstractTestCase
{
    use IndexesTrait;

    public function test()
    {
        $this->assertSame([0], indexes_of($this->list, 'value1'));
        $this->assertSame([0], indexes_of($this->listIterator, 'value1'));
        $this->assertSame([1, 2], indexes_of($this->list, 'value'));
        $this->assertSame([1, 2], indexes_of($this->listIterator, 'value'));
        $this->assertSame([3], indexes_of($this->list, 'value2'));
        $this->assertSame([3], indexes_of($this->listIterator, 'value2'));
        $this->assertSame(['k1', 'k3'], indexes_of($this->hash, 'val1'));
        $this->assertSame(['k1', 'k3'], indexes_of($this->hashIterator, 'val1'));
        $this->assertSame(['k2'], indexes_of($this->hash, 'val2'));
        $this->assertSame(['k2'], indexes_of($this->hashIterator, 'val2'));
        $this->assertSame(['k4'], indexes_of($this->hash, 'val3'));
        $this->assertSame(['k4'], indexes_of($this->hashIterator, 'val3'));
    }

    public function testIfValueCouldNotBeFoundEmptyArrayIsReturned()
    {
        $this->assertSame([], indexes_of($this->list, 'invalidValue'));
        $this->assertSame([], indexes_of($this->listIterator, 'invalidValue'));
        $this->assertSame([], indexes_of($this->hash, 'invalidValue'));
        $this->assertSame([], indexes_of($this->hashIterator, 'invalidValue'));
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\indexes_of() expects parameter 1 to be array or instance of Traversable');
        indexes_of('invalidCollection', 'idx');
    }
}
