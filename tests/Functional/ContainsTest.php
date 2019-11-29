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

use function Functional\contains;

class ContainsTest extends AbstractTestCase
{
    /** @before */
    public function createTestData()
    {
        $this->list = ['value0', 'value1', 'value2', 2];
        $this->listIterator = new ArrayIterator($this->list);
        $this->hash = ['k1' => 'val1', 'k2' => 'val2', 'k3' => 'val3', 'k4' => 2];
        $this->hashIterator = new ArrayIterator($this->hash);
    }

    public function test()
    {
        $this->assertFalse(contains([], 'foo'));
        $this->assertFalse(contains(new ArrayIterator(), 'foo'));

        $this->assertTrue(contains($this->list, 'value0'));
        $this->assertTrue(contains($this->list, 'value1'));
        $this->assertTrue(contains($this->list, 'value2'));
        $this->assertTrue(contains($this->list, 2));
        $this->assertFalse(contains($this->list, '2', true));
        $this->assertFalse(contains($this->list, '2'));
        $this->assertTrue(contains($this->list, '2', false));
        $this->assertFalse(contains($this->list, 'value'));

        $this->assertTrue(contains($this->listIterator, 'value0'));
        $this->assertTrue(contains($this->listIterator, 'value1'));
        $this->assertTrue(contains($this->listIterator, 'value2'));
        $this->assertTrue(contains($this->listIterator, 2));
        $this->assertFalse(contains($this->listIterator, '2', true));
        $this->assertFalse(contains($this->listIterator, '2'));
        $this->assertTrue(contains($this->listIterator, '2', false));
        $this->assertFalse(contains($this->listIterator, 'value'));

        $this->assertTrue(contains($this->hash, 'val1'));
        $this->assertTrue(contains($this->hash, 'val2'));
        $this->assertTrue(contains($this->hash, 'val3'));
        $this->assertTrue(contains($this->hash, 2));
        $this->assertFalse(contains($this->hash, '2', true));
        $this->assertFalse(contains($this->hash, '2'));
        $this->assertTrue(contains($this->hash, '2', false));
        $this->assertFalse(contains($this->hash, 'value'));

        $this->assertTrue(contains($this->hashIterator, 'val1'));
        $this->assertTrue(contains($this->hashIterator, 'val2'));
        $this->assertTrue(contains($this->hashIterator, 'val3'));
        $this->assertTrue(contains($this->hashIterator, 2));
        $this->assertFalse(contains($this->hashIterator, '2', true));
        $this->assertFalse(contains($this->hashIterator, '2'));
        $this->assertTrue(contains($this->hashIterator, '2', false));
        $this->assertFalse(contains($this->hashIterator, 'value'));
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\contains() expects parameter 1 to be array or instance of Traversable');
        contains('invalidCollection', 'value');
    }
}
