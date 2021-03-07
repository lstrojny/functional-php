<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use stdClass;
use ArrayIterator;
use Traversable;

use function Functional\average;

class AverageTest extends AbstractTestCase
{
    /** @var array */
    private $list2;

    /** @var Traversable */
    private $listIterator2;

    /** @var array */
    private $list3;

    /** @var Traversable */
    private $listIterator3;

    /** @var array */
    private $hash2;

    /** @var Traversable */
    private $hashIterator2;

    /** @var array */
    private $hash3;

    /** @var Traversable */
    private $hashIterator3;

    /** @before */
    public function createTestData(): void
    {
        $this->hash = ['f0' => 12, 'f1' => 2, 'f3' => true, 'f4' => false, 'f5' => 'str', 'f6' => [], 'f7' => new stdClass(), 'f8' => 1];
        $this->hashIterator = new ArrayIterator($this->hash);
        $this->list = \array_values($this->hash);
        $this->listIterator = new ArrayIterator($this->list);

        $this->hash2 = ['f0' => 1.0, 'f1' => 0.5, 'f3' => true, 'f4' => false, 'f5' => 1];
        $this->hashIterator2 = new ArrayIterator($this->hash2);
        $this->list2 = \array_values($this->hash2);
        $this->listIterator2 = new ArrayIterator($this->list2);

        $this->hash3 = ['f0' => [], 'f1' => new stdClass(), 'f2' => null, 'f3' => 'foo'];
        $this->hashIterator3 = new ArrayIterator($this->hash3);
        $this->list3 = \array_values($this->hash3);
        $this->listIterator3 = new ArrayIterator($this->list3);
    }

    public function test(): void
    {
        self::assertSame(5, average($this->hash));
        self::assertSame(5, average($this->hashIterator));
        self::assertSame(5, average($this->list));
        self::assertSame(5, average($this->listIterator));

        self::assertEqualsWithDelta(0.833333333, average($this->hash2), 0.001);
        self::assertEqualsWithDelta(0.833333333, average($this->hashIterator2), 0.001);
        self::assertEqualsWithDelta(0.833333333, average($this->list2), 0.001);
        self::assertEqualsWithDelta(0.833333333, average($this->listIterator2), 0.001);

        self::assertNull(average($this->hash3));
        self::assertNull(average($this->hashIterator3));
        self::assertNull(average($this->list3));
        self::assertNull(average($this->listIterator3));
    }

    public function testPassNoCollection(): void
    {
        $this->expectArgumentError('Functional\average() expects parameter 1 to be array or instance of Traversable');
        average('invalidCollection');
    }
}
