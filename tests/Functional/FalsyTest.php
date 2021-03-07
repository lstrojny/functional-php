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
use Traversable;

use function Functional\falsy;

class FalsyTest extends AbstractTestCase
{
    /** @var array */
    private $trueArray;

    /** @var Traversable */
    private $trueIterator;

    /** @var array */
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
        $this->trueArray = [false, null, false, false, 0];
        $this->trueIterator = new ArrayIterator($this->trueArray);
        $this->trueHash = ['k1' => false, 'k2' => null, 'k3' => false, 'k4' => 0];
        $this->trueHashIterator = new ArrayIterator($this->trueHash);
        $this->falseArray = [false, null, 0, 'foo'];
        $this->falseIterator = new ArrayIterator($this->falseArray);
        $this->falseHash = ['k1' => false, 'k2' => 0, 'k3' => true, 'k4' => null];
        $this->falseHashIterator = new ArrayIterator($this->falseHash);
    }

    public function test(): void
    {
        self::assertTrue(falsy([]));
        self::assertTrue(falsy(new ArrayIterator([])));
        self::assertTrue(falsy($this->trueArray));
        self::assertTrue(falsy($this->trueIterator));
        self::assertTrue(falsy($this->trueHash));
        self::assertTrue(falsy($this->trueHashIterator));
        self::assertFalse(falsy($this->falseArray));
        self::assertFalse(falsy($this->falseIterator));
        self::assertFalse(falsy($this->falseHash));
        self::assertFalse(falsy($this->falseHashIterator));
    }

    public function testPassNoCollection(): void
    {
        $this->expectArgumentError('Functional\falsy() expects parameter 1 to be array or instance of Traversable');
        falsy('invalidCollection');
    }
}
