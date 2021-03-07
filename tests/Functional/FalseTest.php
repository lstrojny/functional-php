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
use Functional as F;
use Traversable;

class FalseTest extends AbstractTestCase
{
    /** @var false[] */
    private $trueArray;

    /** @var Traversable */
    private $trueIterator;

    /** @var false[] */
    private $trueHash;

    /** @var Traversable */
    private $trueHashIterator;

    /** @var array */
    private $falseArray;

    /** @var Traversable */
    private $falseIterator;

    /** @var array */
    private $falseHash;

    /** @var Traversable */
    private $falseHashIterator;

    public function setUp(): void
    {
        parent::setUp();
        $this->trueArray = [false, false, false, false];
        $this->trueIterator = new ArrayIterator($this->trueArray);
        $this->trueHash = ['k1' => false, 'k2' => false, 'k3' => false];
        $this->trueHashIterator = new ArrayIterator($this->trueHash);
        $this->falseArray = [false, 0, false, 'foo', [], (object)[]];
        $this->falseIterator = new ArrayIterator($this->falseArray);
        $this->falseHash = ['k1' => false, 'k2' => 0, 'k3' => false];
        $this->falseHashIterator = new ArrayIterator($this->falseHash);
    }

    public function test(): void
    {
        self::assertTrue(F\false([]));
        self::assertTrue(F\false(new ArrayIterator([])));
        self::assertTrue(F\false($this->trueArray));
        self::assertTrue(F\false($this->trueIterator));
        self::assertTrue(F\false($this->trueHash));
        self::assertTrue(F\false($this->trueHashIterator));
        self::assertFalse(F\false($this->falseArray));
        self::assertFalse(F\false($this->falseIterator));
        self::assertFalse(F\false($this->falseHash));
        self::assertFalse(F\false($this->falseHashIterator));
    }

    public function testPassNoCollection(): void
    {
        $this->expectArgumentError('Functional\false() expects parameter 1 to be array or instance of Traversable');
        F\false('invalidCollection');
    }
}
