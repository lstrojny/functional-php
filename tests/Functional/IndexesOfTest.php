<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\indexes_of;

class IndexesOfTest extends AbstractTestCase
{
    use IndexesTrait;

    public function test(): void
    {
        self::assertSame([0], indexes_of($this->list, 'value1'));
        self::assertSame([0], indexes_of($this->listIterator, 'value1'));
        self::assertSame([1, 2], indexes_of($this->list, 'value'));
        self::assertSame([1, 2], indexes_of($this->listIterator, 'value'));
        self::assertSame([3], indexes_of($this->list, 'value2'));
        self::assertSame([3], indexes_of($this->listIterator, 'value2'));
        self::assertSame(['k1', 'k3'], indexes_of($this->hash, 'val1'));
        self::assertSame(['k1', 'k3'], indexes_of($this->hashIterator, 'val1'));
        self::assertSame(['k2'], indexes_of($this->hash, 'val2'));
        self::assertSame(['k2'], indexes_of($this->hashIterator, 'val2'));
        self::assertSame(['k4'], indexes_of($this->hash, 'val3'));
        self::assertSame(['k4'], indexes_of($this->hashIterator, 'val3'));
    }

    public function testIfValueCouldNotBeFoundEmptyArrayIsReturned(): void
    {
        self::assertSame([], indexes_of($this->list, 'invalidValue'));
        self::assertSame([], indexes_of($this->listIterator, 'invalidValue'));
        self::assertSame([], indexes_of($this->hash, 'invalidValue'));
        self::assertSame([], indexes_of($this->hashIterator, 'invalidValue'));
    }

    public function testPassNoCollection(): void
    {
        $this->expectArgumentError('Functional\indexes_of() expects parameter 1 to be array or instance of Traversable');
        indexes_of('invalidCollection', 'idx');
    }
}
