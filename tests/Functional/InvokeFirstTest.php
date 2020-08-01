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

use function Functional\invoke_first;

class InvokeFirstTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->list = [$this, null, null];
        $this->listIterator = new ArrayIterator($this->list);
        $this->keyArray = ['k1' => $this, 'k2' => null];
        $this->keyIterator = new ArrayIterator(['k1' => $this, 'k2' => null]);

        $this->arrayVeryFirstNotCallable = [null, $this, null, null];
        $this->iteratorVeryFirstNotCallable = new ArrayIterator($this->arrayVeryFirstNotCallable);
    }

    public function testSimple()
    {
        $this->assertSame('methodValue', invoke_first($this->list, 'method', [1, 2]));
        $this->assertSame('methodValue', invoke_first($this->listIterator, 'method'));
        $this->assertSame(null, invoke_first($this->list, 'undefinedMethod'));
        $this->assertSame(null, invoke_first($this->list, 'setExpectedExceptionFromAnnotation'), 'Protected method');
        $this->assertSame([1, 2], invoke_first($this->list, 'returnArguments', [1, 2]));
        $this->assertSame('methodValue', invoke_first($this->keyArray, 'method'));
        $this->assertSame('methodValue', invoke_first($this->keyIterator, 'method'));
    }

    public function testSkipNonCallables()
    {
        $this->assertSame('methodValue', invoke_first($this->arrayVeryFirstNotCallable, 'method', [1, 2]));
        $this->assertSame('methodValue', invoke_first($this->iteratorVeryFirstNotCallable, 'method'));
        $this->assertSame(null, invoke_first($this->arrayVeryFirstNotCallable, 'undefinedMethod'));
        $this->assertSame(null, invoke_first($this->arrayVeryFirstNotCallable, 'setExpectedExceptionFromAnnotation'), 'Protected method');
        $this->assertSame([1, 2], invoke_first($this->arrayVeryFirstNotCallable, 'returnArguments', [1, 2]));
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\invoke_first() expects parameter 1 to be array or instance of Traversable');
        invoke_first('invalidCollection', 'method');
    }

    public function testPassNoPropertyName()
    {
        $this->expectArgumentError('Functional\invoke_first() expects parameter 2 to be string');
        invoke_first($this->list, new \stdClass());
    }

    public function testException()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        invoke_first($this->list, 'exception');
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
