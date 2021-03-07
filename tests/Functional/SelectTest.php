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

use function Functional\select;

class SelectTest extends AbstractTestCase
{
    public function getAliases(): array
    {
        return [
            ['Functional\select'],
            ['Functional\filter'],
        ];
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->list = ['value', 'wrong', 'value'];
        $this->listIterator = new ArrayIterator($this->list);
        $this->hash = ['k1' => 'value', 'k2' => 'wrong', 'k3' => 'value'];
        $this->hashIterator = new ArrayIterator($this->hash);
    }

    /**
     * @dataProvider getAliases
     */
    public function test($functionName): void
    {
        $callback = function ($v, $k, $collection) {
            InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);

            return $v == 'value' && \strlen($k) > 0;
        };
        self::assertSame(['value', 2 => 'value'], $functionName($this->list, $callback));
        self::assertSame(['value', 2 => 'value'], $functionName($this->listIterator, $callback));
        self::assertSame(['k1' => 'value', 'k3' => 'value'], $functionName($this->hash, $callback));
        self::assertSame(['k1' => 'value', 'k3' => 'value'], $functionName($this->hashIterator, $callback));
    }

    /**
     * @dataProvider getAliases
     */
    public function testPassNonCallable($functionName): void
    {
        $this->expectCallableArgumentError($functionName, 2);
        $functionName($this->list, 'undefinedFunction');
    }

    public function testPassNoCallable(): void
    {
        self::assertSame(['value', 'wrong', 'value'], select($this->list));
        self::assertSame(['value', 'wrong', 'value'], select($this->listIterator));
        self::assertSame(['k1' => 'value', 'k2' => 'wrong', 'k3' => 'value'], select($this->hash));
        self::assertSame(['k1' => 'value', 'k2' => 'wrong', 'k3' => 'value'], select($this->hashIterator));
        self::assertSame([0 => true, 2 => true], select([true, false, true]));
    }

    /**
     * @dataProvider getAliases
     */
    public function testPassNoCollection($functionName): void
    {
        $this->expectArgumentError(
            \sprintf(
                '%s() expects parameter 1 to be array or instance of Traversable',
                $functionName
            )
        );
        $functionName('invalidCollection', 'strlen');
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
    public function testExceptionIsThrownInHash($functionName): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        $functionName($this->hash, [$this, 'exception']);
    }

    /**
     * @dataProvider getAliases
     */
    public function testExceptionIsThrownInIterator($functionName): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        $functionName($this->listIterator, [$this, 'exception']);
    }

    /**
     * @dataProvider getAliases
     */
    public function testExceptionIsThrownInHashIterator($functionName): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        $functionName($this->hashIterator, [$this, 'exception']);
    }
}
