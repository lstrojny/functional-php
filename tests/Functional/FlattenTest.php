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
use stdClass;
use Traversable;

use function Functional\flatten;

class FlattenTest extends AbstractTestCase
{
    /** @var array */
    private $goodArray;

    /** @var array */
    private $goodArray2;

    /** @var Traversable */
    private $goodIterator;

    public function setUp(): void
    {
        parent::setUp();
        $this->goodArray = [1, 2, 3, [4, 5, 6, [7, 8, 9]], 10, [11, [12, 13], 14], 15];
        $this->goodArray2 = [1 => 1, "foo" => "2", 3 => "3", ["foo" => 5]];
        $this->goodIterator = new ArrayIterator($this->goodArray);
        $this->goodIterator[3] = new ArrayIterator($this->goodIterator[3]);
        $this->goodIterator[5][1] = new ArrayIterator($this->goodIterator[5][1]);
    }

    public function test(): void
    {
        self::assertSame(\range(1, 15), flatten($this->goodArray));
        self::assertSame(\range(1, 15), flatten($this->goodIterator));
        self::assertSame([1, "2", "3", 5], flatten($this->goodArray2));
        self::assertEquals([new stdClass()], flatten([[new stdClass()]]));
        self::assertSame([null, null], flatten([[null], null]));
    }

    public function testPassNoCollection(): void
    {
        $this->expectArgumentError('Functional\flatten() expects parameter 1 to be array or instance of Traversable');
        flatten('invalidCollection');
    }
}
