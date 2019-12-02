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
use Functional as F;

use function Functional\true;

class TrueTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->trueArray = [true, true, true, true];
        $this->trueIterator = new ArrayIterator($this->trueArray);
        $this->trueHash = ['k1' => true, 'k2' => true, 'k3' => true];
        $this->trueHashIterator = new ArrayIterator($this->trueHash);
        $this->falseArray = [true, 1, true];
        $this->falseIterator = new ArrayIterator($this->falseArray);
        $this->falseHash = ['k1' => true, 'k2' => 1, 'k3' => true];
        $this->falseHashIterator = new ArrayIterator($this->falseHash);
    }

    public function test()
    {
        $this->assertTrue(F\true([]));
        $this->assertTrue(F\true(new ArrayIterator([])));
        $this->assertTrue(F\true($this->trueArray));
        $this->assertTrue(F\true($this->trueIterator));
        $this->assertTrue(F\true($this->trueHash));
        $this->assertTrue(F\true($this->trueHashIterator));
        $this->assertFalse(F\true($this->falseArray));
        $this->assertFalse(F\true($this->falseIterator));
        $this->assertFalse(F\true($this->falseHash));
        $this->assertFalse(F\true($this->falseHashIterator));
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\true() expects parameter 1 to be array or instance of Traversable');
        F\true('invalidCollection');
    }
}
