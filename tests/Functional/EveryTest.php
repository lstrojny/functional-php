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
use Traversable;

use function Functional\every;

class EveryTest extends AbstractTestCase
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
        $this->badArray = ['value', 'nope', 'value'];
        $this->badIterator = new ArrayIterator($this->badArray);
    }

    public function test(): void
    {
        self::assertTrue(every($this->goodArray, [$this, 'functionalCallback']));
        self::assertTrue(every($this->goodIterator, [$this, 'functionalCallback']));
        self::assertFalse(every($this->badArray, [$this, 'functionalCallback']));
        self::assertFalse(every($this->badIterator, [$this, 'functionalCallback']));
    }

    public function testPassNonCallable(): void
    {
        $this->expectCallableArgumentError('Functional\every', 2);
        every($this->goodArray, 'undefinedFunction');
    }

    public function testPassNoCollection(): void
    {
        $this->expectArgumentError('Functional\every() expects parameter 1 to be array or instance of Traversable');
        every('invalidCollection', 'strlen');
    }

    public function testPassNoCallable(): void
    {
        self::assertTrue(every($this->goodArray));
        self::assertTrue(every($this->goodIterator));
        self::assertTrue(every($this->badArray));
        self::assertTrue(every($this->badIterator));
    }

    public function testExceptionIsThrownInArray(): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        every($this->goodArray, [$this, 'exception']);
    }

    public function testExceptionIsThrownInCollection(): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        every($this->goodIterator, [$this, 'exception']);
    }

    public function functionalCallback($value, $key, $collection): bool
    {
        InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);

        return $value == 'value' && \is_numeric($key);
    }
}
