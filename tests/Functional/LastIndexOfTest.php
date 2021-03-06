<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\last_index_of;

class LastIndexOfTest extends AbstractTestCase
{
    use IndexesTrait;

    public function test(): void
    {
        self::assertSame(0, last_index_of($this->list, 'value1'));
        self::assertSame(0, last_index_of($this->listIterator, 'value1'));
        self::assertSame(2, last_index_of($this->list, 'value'));
        self::assertSame(2, last_index_of($this->listIterator, 'value'));
        self::assertSame(3, last_index_of($this->list, 'value2'));
        self::assertSame(3, last_index_of($this->listIterator, 'value2'));
        self::assertSame('k3', last_index_of($this->hash, 'val1'));
        self::assertSame('k3', last_index_of($this->hashIterator, 'val1'));
        self::assertSame('k2', last_index_of($this->hash, 'val2'));
        self::assertSame('k2', last_index_of($this->hashIterator, 'val2'));
        self::assertSame('k4', last_index_of($this->hash, 'val3'));
        self::assertSame('k4', last_index_of($this->hashIterator, 'val3'));
    }

    public function testIfValueCouldNotBeFoundFalseIsReturned(): void
    {
        self::assertFalse(last_index_of($this->list, 'invalidValue'));
        self::assertFalse(last_index_of($this->listIterator, 'invalidValue'));
        self::assertFalse(last_index_of($this->hash, 'invalidValue'));
        self::assertFalse(last_index_of($this->hashIterator, 'invalidValue'));
    }

    public function testPassCallback(): void
    {
        self::assertSame(
            3,
            last_index_of($this->list, function ($element) {
                return $element;
            })
        );
    }

    public function testPassNoCollection(): void
    {
        $this->expectArgumentError('Functional\last_index_of() expects parameter 1 to be array or instance of Traversable');
        last_index_of('invalidCollection', 'idx');
    }
}
