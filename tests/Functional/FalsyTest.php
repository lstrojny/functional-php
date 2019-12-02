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

use function Functional\falsy;

class FalsyTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->trueArray = [false, null, false, false, 0];
        $this->trueIterator = new ArrayIterator($this->trueArray);
        $this->trueHash = ['k1' => false, 'k2' => null, 'k3' => false, 'k4' => 0];
        $this->trueHashIterator = new ArrayIterator($this->trueHash);
        $this->falseArray = [false, null, 0, 'foo'];
        $this->falseIterator = new ArrayIterator($this->falseArray);
        $this->falseHash = ['k1' => false, 'k2' => 0, 'k3' => true, 'k4' => null];
        $this->falseHashIterator = new ArrayIterator($this->falseHash);
    }

    public function test()
    {
        $this->assertTrue(falsy([]));
        $this->assertTrue(falsy(new ArrayIterator([])));
        $this->assertTrue(falsy($this->trueArray));
        $this->assertTrue(falsy($this->trueIterator));
        $this->assertTrue(falsy($this->trueHash));
        $this->assertTrue(falsy($this->trueHashIterator));
        $this->assertFalse(falsy($this->falseArray));
        $this->assertFalse(falsy($this->falseIterator));
        $this->assertFalse(falsy($this->falseHash));
        $this->assertFalse(falsy($this->falseHashIterator));
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\falsy() expects parameter 1 to be array or instance of Traversable');
        falsy('invalidCollection');
    }
}
