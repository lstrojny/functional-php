<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use ArrayIterator;

use function Functional\invoke_last;

class InvokeLastTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->list = [null, null, $this];
        $this->listIterator = new ArrayIterator($this->list);
        $this->keyArray = ['k1' => null, 'k2' => $this];
        $this->keyIterator = new ArrayIterator(['k1' => null, 'k2' => $this]);

        $this->arrayVeryLastNotCallable = [null, null, $this, null];
        $this->iteratorVeryLastNotCallable = new ArrayIterator($this->arrayVeryLastNotCallable);
    }

    public function testSimple()
    {
        $this->assertSame('methodValue', invoke_last($this->list, 'method', [1, 2]));
        $this->assertSame('methodValue', invoke_last($this->listIterator, 'method'));
        $this->assertSame(null, invoke_last($this->list, 'undefinedMethod'));
        $this->assertSame(null, invoke_last($this->list, 'setExpectedExceptionFromAnnotation'), 'Protected method');
        $this->assertSame([1, 2], invoke_last($this->list, 'returnArguments', [1, 2]));
        $this->assertSame('methodValue', invoke_last($this->keyArray, 'method'));
        $this->assertSame('methodValue', invoke_last($this->keyIterator, 'method'));
    }

    public function testSkipNonCallables()
    {
        $this->assertSame('methodValue', invoke_last($this->arrayVeryLastNotCallable, 'method', [1, 2]));
        $this->assertSame('methodValue', invoke_last($this->iteratorVeryLastNotCallable, 'method'));
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\invoke_last() expects parameter 1 to be array or instance of Traversable');
        invoke_last('invalidCollection', 'method');
    }

    public function testPassNoPropertyName()
    {
        $this->expectArgumentError('Functional\invoke_last() expects parameter 2 to be string');
        invoke_last($this->list, new \stdClass());
    }

    public function testException()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        invoke_last($this->list, 'exception');
    }

    public function method()
    {
        return 'methodValue';
    }

    public function returnArguments()
    {
        return func_get_args();
    }
}
