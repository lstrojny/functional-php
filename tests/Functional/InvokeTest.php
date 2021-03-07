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

use function Functional\invoke;

class InvokeTest extends AbstractTestCase
{
    /** @var object[] */
    private $keyArray;

    /** @var Traversable */
    private $keyIterator;

    public function setUp(): void
    {
        parent::setUp();
        $this->list = [$this, $this, $this];
        $this->listIterator = new ArrayIterator($this->list);
        $this->keyArray = ['k1' => $this, 'k2' => $this];
        $this->keyIterator = new ArrayIterator(['k1' => $this, 'k2' => $this]);
    }

    public function test(): void
    {
        self::assertSame(['methodValue', 'methodValue', 'methodValue'], invoke($this->list, 'method', [1, 2]));
        self::assertSame(['methodValue', 'methodValue', 'methodValue'], invoke($this->listIterator, 'method'));
        self::assertSame([null, null, null], invoke($this->list, 'undefinedMethod'));
        self::assertSame([null, null, null], invoke($this->list, 'setExpectedExceptionFromAnnotation'), 'Protected method');
        self::assertSame([[1, 2], [1, 2], [1, 2]], invoke($this->list, 'returnArguments', [1, 2]));
        self::assertSame(['k1' => 'methodValue', 'k2' => 'methodValue'], invoke($this->keyArray, 'method'));
        self::assertSame(['k1' => 'methodValue', 'k2' => 'methodValue'], invoke($this->keyIterator, 'method'));
    }

    public function testPassNoCollection(): void
    {
        $this->expectArgumentError('Functional\invoke() expects parameter 1 to be array or instance of Traversable');
        invoke('invalidCollection', 'method');
    }

    public function testPassNoPropertyName(): void
    {
        $this->expectArgumentError('Functional\invoke() expects parameter 2 to be string');
        invoke($this->list, new \stdClass());
    }

    public function testException(): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        invoke($this->list, 'exception');
    }

    public function method(): string
    {
        return 'methodValue';
    }

    public function returnArguments(): array
    {
        return \func_get_args();
    }
}
