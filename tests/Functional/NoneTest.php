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
use Functional\Exceptions\InvalidArgumentException;

use function Functional\none;

class NoneTest extends AbstractTestCase
{
    /** @var string[] */
    private $goodArray;

    /** @var Traversable|string[] */
    private $goodIterator;

    /** @var string[] */
    private $badArray;

    /** @var Traversable|string[] */
    private $badIterator;

    public function setUp(): void
    {
        parent::setUp();
        $this->goodArray = ['value', 'value', 'value'];
        $this->goodIterator = new ArrayIterator($this->goodArray);
        $this->badArray = ['value', 'value', 'foo'];
        $this->badIterator = new ArrayIterator($this->badArray);
    }

    public function test(): void
    {
        self::assertTrue(none($this->goodArray, [$this, 'functionalCallback']));
        self::assertTrue(none($this->goodIterator, [$this, 'functionalCallback']));
        self::assertFalse(none($this->badArray, [$this, 'functionalCallback']));
        self::assertFalse(none($this->badIterator, [$this, 'functionalCallback']));
    }

    public function testPassNoCollection(): void
    {
        $this->expectArgumentError('Functional\none() expects parameter 1 to be array or instance of Traversable');
        none('invalidCollection', 'strlen');
    }

    public function testPassNonCallable(): void
    {
        $this->expectCallableArgumentError('Functional\none', 2);
        none($this->goodArray, 'undefinedFunction');
    }

    public function testPassNoCallable(): void
    {
        self::assertFalse(none($this->goodArray));
        self::assertFalse(none($this->goodIterator));
        self::assertFalse(none($this->badArray));
        self::assertFalse(none($this->badIterator));
    }

    public function testExceptionIsThrownInArray(): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        none($this->goodArray, [$this, 'exception']);
    }

    public function testExceptionIsThrownInIterator(): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        none($this->goodIterator, [$this, 'exception']);
    }

    public function functionalCallback($value, $key, $collection): bool
    {
        InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
        return $value != 'value' && \strlen($key) > 0;
    }
}
