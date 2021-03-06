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

use function Functional\invoke_last;

class InvokeLastTest extends AbstractTestCase
{
    private $iteratorVeryLastNotCallable;

    private $arrayVeryLastNotCallable;

    private $keyIterator;

    private $keyArray;

    public function setUp(): void
    {
        parent::setUp();
        $this->list = [null, null, $this];
        $this->listIterator = new ArrayIterator($this->list);
        $this->keyArray = ['k1' => null, 'k2' => $this];
        $this->keyIterator = new ArrayIterator(['k1' => null, 'k2' => $this]);

        $this->arrayVeryLastNotCallable = [null, null, $this, null];
        $this->iteratorVeryLastNotCallable = new ArrayIterator($this->arrayVeryLastNotCallable);
    }

    public function testSimple(): void
    {
        self::assertSame('methodValue', invoke_last($this->list, 'method', [1, 2]));
        self::assertSame('methodValue', invoke_last($this->listIterator, 'method'));
        self::assertNull(invoke_last($this->list, 'undefinedMethod'));
        self::assertNull(invoke_last($this->list, 'setExpectedExceptionFromAnnotation'), 'Protected method');
        self::assertSame([1, 2], invoke_last($this->list, 'returnArguments', [1, 2]));
        self::assertSame('methodValue', invoke_last($this->keyArray, 'method'));
        self::assertSame('methodValue', invoke_last($this->keyIterator, 'method'));
    }

    public function testSkipNonCallables(): void
    {
        self::assertSame('methodValue', invoke_last($this->arrayVeryLastNotCallable, 'method', [1, 2]));
        self::assertSame('methodValue', invoke_last($this->iteratorVeryLastNotCallable, 'method'));
    }

    public function testPassNoCollection(): void
    {
        $this->expectArgumentError('Functional\invoke_last() expects parameter 1 to be array or instance of Traversable');
        invoke_last('invalidCollection', 'method');
    }

    public function testPassNoPropertyName(): void
    {
        $this->expectArgumentError('Functional\invoke_last() expects parameter 2 to be string');
        invoke_last($this->list, new \stdClass());
    }

    public function testException(): void
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        invoke_last($this->list, 'exception');
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
