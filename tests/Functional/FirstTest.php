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

class FirstTest extends AbstractTestCase
{
    /** @var string[] */
    private $badArray;

    /** @var Traversable */
    private $badIterator;

    public function getAliases(): array
    {
        return [
            ['Functional\first'],
            ['Functional\head'],
        ];
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->list = ['first', 'second', 'third'];
        $this->listIterator = new ArrayIterator($this->list);
        $this->badArray = ['foo', 'bar', 'baz'];
        $this->badIterator = new ArrayIterator($this->badArray);
    }

    /**
     * @dataProvider getAliases
     */
    public function test(string $functionName): void
    {
        $callback = function ($v, $k, $collection) {
            InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);
            return $v == 'second' && $k == 1;
        };

        self::assertSame('second', $functionName($this->list, $callback));
        self::assertSame('second', $functionName($this->listIterator, $callback));
        self::assertNull($functionName($this->badArray, $callback));
        self::assertNull($functionName($this->badIterator, $callback));
    }

    /**
     * @dataProvider getAliases
     */
    public function testWithoutCallback($functionName): void
    {
        self::assertSame('first', $functionName($this->list));
        self::assertSame('first', $functionName($this->list, null));
        self::assertSame('first', $functionName($this->listIterator));
        self::assertSame('first', $functionName($this->listIterator, null));
        self::assertSame('foo', $functionName($this->badArray));
        self::assertSame('foo', $functionName($this->badArray, null));
        self::assertSame('foo', $functionName($this->badIterator));
        self::assertSame('foo', $functionName($this->badIterator, null));
    }

    /**
     * @dataProvider getAliases
     */
    public function testPassNonCallable($functionName): void
    {
        $this->expectCallableArgumentError($functionName, 2);
        $functionName($this->list, 'undefinedFunction');
    }

    /**
     * @dataProvider getAliases
     */
    public function testExceptionIsThrownInArray($functionName): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        $functionName($this->list, [$this, 'exception']);
    }

    /**
     * @dataProvider getAliases
     */
    public function testExceptionIsThrownInCollection($functionName): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        $functionName($this->listIterator, [$this, 'exception']);
    }

    /**
     * @dataProvider getAliases
     */
    public function testPassNoCollection($functionName): void
    {
        $this->expectArgumentError(\sprintf('%s() expects parameter 1 to be array or instance of Traversable', $functionName));
        $functionName('invalidCollection', 'strlen');
    }
}
