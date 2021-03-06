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
use PHPUnit\Framework\MockObject\MockObject;

use function Functional\each;

class EachTest extends AbstractTestCase
{
    /**
     * @var MockObject
     */
    private $cb;

    public function setUp(): void
    {
        parent::setUp();
        $this->cb = $this->getMockBuilder('cb')
                         ->setMethods(['call'])
                         ->getMock();


        $this->list = ['value0', 'value1', 'value2', 'value3'];
        $this->listIterator = new ArrayIterator($this->list);
        $this->hash = ['k0' => 'value0', 'k1' => 'value1', 'k2' => 'value2'];
        $this->hashIterator = new ArrayIterator($this->hash);
    }

    public function testArray(): void
    {
        $this->prepareCallback($this->list);
        self::assertNull(each($this->list, [$this->cb, 'call']));
    }

    public function testIterator(): void
    {
        $this->prepareCallback($this->listIterator);
        self::assertNull(each($this->listIterator, [$this->cb, 'call']));
    }

    public function testHash(): void
    {
        $this->prepareCallback($this->hash);
        self::assertNull(each($this->hash, [$this->cb, 'call']));
    }

    public function testHashIterator(): void
    {
        $this->prepareCallback($this->hashIterator);
        self::assertNull(each($this->hashIterator, [$this->cb, 'call']));
    }

    public function testExceptionIsThrownInArray(): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        each($this->list, [$this, 'exception']);
    }

    public function testExceptionIsThrownInCollection(): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        each($this->listIterator, [$this, 'exception']);
    }

    private function prepareCallback($collection): void
    {
        $args = [];

        foreach ($collection as $key => $value) {
            $args[] = [$value, $key, $collection];
        }

        $this->cb->method('call')
            ->withConsecutive(...$args);
    }

    public function testPassNonCallable(): void
    {
        $this->expectCallableArgumentError('Functional\each', 2);
        each($this->list, 'undefinedFunction');
    }

    public function testPassNoCollection(): void
    {
        $this->expectArgumentError('Functional\each() expects parameter 1 to be array or instance of Traversable');
        each('invalidCollection', 'strlen');
    }
}
