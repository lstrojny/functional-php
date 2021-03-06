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

use function Functional\difference;

class DifferenceTest extends AbstractTestCase
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
        $this->floatArray = ["foo" => 4.5, 1.1, 1];
        $this->floatIterator = new ArrayIterator($this->floatArray);
    }

    public function test(): void
    {
        self::assertSame(-10, difference($this->intArray));
        self::assertSame(-10, difference($this->intIterator));
        self::assertEqualsWithDelta(-6.6, difference($this->floatArray), 0.01, '');
        self::assertEqualsWithDelta(-6.6, difference($this->floatIterator), 0.01, '');
        self::assertSame(0, difference($this->intArray, 10));
        self::assertSame(0, difference($this->intIterator, 10));
        self::assertEqualsWithDelta(-10, difference($this->floatArray, -3.4), 0.01, '');
        self::assertEqualsWithDelta(-10, difference($this->floatIterator, -3.4), 0.01, '');
    }

    /** @dataProvider Functional\Tests\MathDataProvider::injectErrorCollection */
    public function testElementsOfWrongTypeAreIgnored($collection): void
    {
        self::assertEqualsWithDelta(-3.5, difference($collection), 0.1, '');
    }

    public function testPassNoCollection(): void
    {
        $this->expectArgumentError('Functional\difference() expects parameter 1 to be array or instance of Traversable');
        difference('invalidCollection', 'strlen');
    }
}
