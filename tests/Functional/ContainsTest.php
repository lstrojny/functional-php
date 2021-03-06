<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use ArrayIterator;

use function Functional\contains;

class ContainsTest extends AbstractTestCase
{
    /** @before */
    public function createTestData(): void
    {
        $this->list = ['value0', 'value1', 'value2', 2];
        $this->listIterator = new ArrayIterator($this->list);
        $this->hash = ['k1' => 'val1', 'k2' => 'val2', 'k3' => 'val3', 'k4' => 2];
        $this->hashIterator = new ArrayIterator($this->hash);
    }

    public function test(): void
    {
        self::assertFalse(contains([], 'foo'));
        self::assertFalse(contains(new ArrayIterator(), 'foo'));

        self::assertTrue(contains($this->list, 'value0'));
        self::assertTrue(contains($this->list, 'value1'));
        self::assertTrue(contains($this->list, 'value2'));
        self::assertTrue(contains($this->list, 2));
        self::assertFalse(contains($this->list, '2', true));
        self::assertFalse(contains($this->list, '2'));
        self::assertTrue(contains($this->list, '2', false));
        self::assertFalse(contains($this->list, 'value'));

        self::assertTrue(contains($this->listIterator, 'value0'));
        self::assertTrue(contains($this->listIterator, 'value1'));
        self::assertTrue(contains($this->listIterator, 'value2'));
        self::assertTrue(contains($this->listIterator, 2));
        self::assertFalse(contains($this->listIterator, '2', true));
        self::assertFalse(contains($this->listIterator, '2'));
        self::assertTrue(contains($this->listIterator, '2', false));
        self::assertFalse(contains($this->listIterator, 'value'));

        self::assertTrue(contains($this->hash, 'val1'));
        self::assertTrue(contains($this->hash, 'val2'));
        self::assertTrue(contains($this->hash, 'val3'));
        self::assertTrue(contains($this->hash, 2));
        self::assertFalse(contains($this->hash, '2', true));
        self::assertFalse(contains($this->hash, '2'));
        self::assertTrue(contains($this->hash, '2', false));
        self::assertFalse(contains($this->hash, 'value'));

        self::assertTrue(contains($this->hashIterator, 'val1'));
        self::assertTrue(contains($this->hashIterator, 'val2'));
        self::assertTrue(contains($this->hashIterator, 'val3'));
        self::assertTrue(contains($this->hashIterator, 2));
        self::assertFalse(contains($this->hashIterator, '2', true));
        self::assertFalse(contains($this->hashIterator, '2'));
        self::assertTrue(contains($this->hashIterator, '2', false));
        self::assertFalse(contains($this->hashIterator, 'value'));
    }

    public function testPassNoCollection(): void
    {
        $this->expectArgumentError('Functional\contains() expects parameter 1 to be array or instance of Traversable');
        contains('invalidCollection', 'value');
    }
}
