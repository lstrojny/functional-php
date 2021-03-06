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

use function Functional\product;

class ProductTest extends AbstractTestCase
{
    /** @var int[] */
    private $intArray;

    /** @var Traversable|int[] */
    private $intIterator;

    /** @var float[] */
    private $floatArray;

    /** @var Traversable|float[] */
    private $floatIterator;

    public function setUp(): void
    {
        parent::setUp();
        $this->intArray = [1 => 1, 2, "foo" => 3, 4];
        $this->intIterator = new ArrayIterator($this->intArray);
        $this->floatArray = ["foo" => 1.5, 1.1, 1];
        $this->floatIterator = new ArrayIterator($this->floatArray);
    }

    public function test(): void
    {
        self::assertSame(240, product($this->intArray, 10));
        self::assertSame(240, product($this->intArray, 10));
        self::assertSame(24, product($this->intArray));
        self::assertSame(24, product($this->intIterator));
        self::assertEqualsWithDelta(1.65, product($this->floatArray), 0.01, '');
        self::assertEqualsWithDelta(1.65, product($this->floatIterator), 0.01, '');
    }

    /** @dataProvider Functional\Tests\MathDataProvider::injectErrorCollection */
    public function testElementsOfWrongTypeAreIgnored($collection): void
    {
        self::assertEqualsWithDelta(3, product($collection), 0.01, '');
    }

    public function testPassNoCollection(): void
    {
        $this->expectArgumentError('Functional\product() expects parameter 1 to be array or instance of Traversable');
        product('invalidCollection', 'strlen');
    }
}
