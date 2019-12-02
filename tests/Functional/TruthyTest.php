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

use function Functional\truthy;

class TruthyTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->trueArray = [true, true, 'foo', true, true, 1];
        $this->trueIterator = new ArrayIterator($this->trueArray);
        $this->trueHash = ['k1' => true, 'k2' => 'foo', 'k3' => true, 'k4' => 1];
        $this->trueHashIterator = new ArrayIterator($this->trueHash);
        $this->falseArray = [true, 0, true, null];
        $this->falseIterator = new ArrayIterator($this->falseArray);
        $this->falseHash = ['k1' => true, 'k2' => 0, 'k3' => true, 'k4' => null];
        $this->falseHashIterator = new ArrayIterator($this->falseHash);
    }

    public function test()
    {
        $this->assertTrue(truthy([]));
        $this->assertTrue(truthy(new ArrayIterator([])));
        $this->assertTrue(truthy($this->trueArray));
        $this->assertTrue(truthy($this->trueIterator));
        $this->assertTrue(truthy($this->trueHash));
        $this->assertTrue(truthy($this->trueHashIterator));
        $this->assertFalse(truthy($this->falseArray));
        $this->assertFalse(truthy($this->falseIterator));
        $this->assertFalse(truthy($this->falseHash));
        $this->assertFalse(truthy($this->falseHashIterator));
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\truthy() expects parameter 1 to be array or instance of Traversable');
        truthy('invalidCollection');
    }
}
