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

use function Functional\product;

class ProductTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->intArray = [1 => 1, 2, "foo" => 3, 4];
        $this->intIterator = new ArrayIterator($this->intArray);
        $this->floatArray = ["foo" => 1.5, 1.1, 1];
        $this->floatIterator = new ArrayIterator($this->floatArray);
    }

    public function test()
    {
        $this->assertSame(240, product($this->intArray, 10));
        $this->assertSame(240, product($this->intArray, 10));
        $this->assertSame(24, product($this->intArray));
        $this->assertSame(24, product($this->intIterator));
        $this->assertEquals(1.65, product($this->floatArray), '', 0.01);
        $this->assertEquals(1.65, product($this->floatIterator), '', 0.01);
    }

    /** @dataProvider Functional\Tests\MathDataProvider::injectErrorCollection */
    public function testElementsOfWrongTypeAreIgnored($collection)
    {
        $this->assertEquals(3, product($collection), '', 0.01);
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\product() expects parameter 1 to be array or instance of Traversable');
        product('invalidCollection', 'strlen');
    }
}
