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
use Traversable;

use function Functional\sum;

class SumTest extends AbstractTestCase
{
    /** @var array */
    private $intArray;

    /** @var Traversable */
    private $intIterator;

    /** @var array */
    private $floatArray;

    /** @var Traversable */
    private $floatIterator;

    public function setUp()
    {
        parent::setUp();
        $this->intArray = [1 => 1, 2, "foo" => 3];
        $this->intIterator = new ArrayIterator($this->intArray);
        $this->floatArray = [1.1, 2.9, 3.5];
        $this->floatIterator = new ArrayIterator($this->floatArray);
    }

    public function test()
    {
        $this->assertSame(6, sum($this->intArray));
        $this->assertSame(6, sum($this->intIterator));
        $this->assertEquals(7.5, sum($this->floatArray), '', 0.01);
        $this->assertEquals(7.5, sum($this->floatIterator), '', 0.01);
        $this->assertSame(10, sum($this->intArray, 4));
        $this->assertSame(10, sum($this->intIterator, 4));
        $this->assertEquals(10, sum($this->floatArray, 2.5), '', 0.01);
        $this->assertEquals(10, sum($this->floatIterator, 2.5), '', 0.01);
    }

    /** @dataProvider Functional\Tests\MathDataProvider::injectErrorCollection */
    public function testElementsOfWrongTypeAreIgnored($collection)
    {
        $this->assertEquals(3.5, sum($collection), '', 0.1);
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\sum() expects parameter 1 to be array or instance of Traversable');
        sum('invalidCollection');
    }
}
