<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\first_index_of;

class FirstIndexOfTest extends AbstractTestCase
{
    use IndexesTrait;

    public function test(): void
    {
        self::assertSame(0, first_index_of($this->list, 'value1'));
        self::assertSame(0, first_index_of($this->listIterator, 'value1'));
        self::assertSame(1, first_index_of($this->list, 'value'));
        self::assertSame(1, first_index_of($this->listIterator, 'value'));
        self::assertSame(3, first_index_of($this->list, 'value2'));
        self::assertSame(3, first_index_of($this->listIterator, 'value2'));
        self::assertSame('k1', first_index_of($this->hash, 'val1'));
        self::assertSame('k1', first_index_of($this->hashIterator, 'val1'));
        self::assertSame('k2', first_index_of($this->hash, 'val2'));
        self::assertSame('k2', first_index_of($this->hashIterator, 'val2'));
        self::assertSame('k4', first_index_of($this->hash, 'val3'));
        self::assertSame('k4', first_index_of($this->hashIterator, 'val3'));
    }

    public function testIfValueCouldNotBeFoundFalseIsReturned(): void
    {
        self::assertFalse(first_index_of($this->list, 'invalidValue'));
        self::assertFalse(first_index_of($this->listIterator, 'invalidValue'));
        self::assertFalse(first_index_of($this->hash, 'invalidValue'));
        self::assertFalse(first_index_of($this->hashIterator, 'invalidValue'));
    }

    public function testPassCallback(): void
    {
        self::assertSame(
            0,
            first_index_of($this->list, function ($element) {
                return $element;
            })
        );
    }

    public function testPassNoCollection(): void
    {
        $this->expectArgumentError('Functional\first_index_of() expects parameter 1 to be array or instance of Traversable');
        first_index_of('invalidCollection', 'idx');
    }
}
