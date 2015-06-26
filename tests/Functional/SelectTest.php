<?php
/**
 * Copyright (C) 2011-2015 by Lars Strojny <lstrojny@php.net>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace Functional\Tests;

use ArrayIterator;
use Functional\Exceptions\InvalidArgumentException;
use function Functional\select;
use function Functional\filter;

class SelectTest extends AbstractTestCase
{
    public function getAliases()
    {
        return [
            ['Functional\select'],
            ['Functional\filter'],
        ];
    }

    public function setUp()
    {
        parent::setUp($this->getAliases());
        $this->list = ['value', 'wrong', 'value'];
        $this->listIterator = new ArrayIterator($this->list);
        $this->hash = ['k1' => 'value', 'k2' => 'wrong', 'k3' => 'value'];
        $this->hashIterator = new ArrayIterator($this->hash);
    }

    /**
     * @dataProvider getAliases
     */
    public function test($functionName)
    {
        $callback = function($v, $k, $collection) {
            InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
            return $v == 'value' && strlen($k) > 0;
        };
        $this->assertSame(['value', 2 => 'value'], $functionName($this->list, $callback));
        $this->assertSame(['value', 2 => 'value'], $functionName($this->listIterator, $callback));
        $this->assertSame(['k1' => 'value', 'k3' => 'value'], $functionName($this->hash, $callback));
        $this->assertSame(['k1' => 'value', 'k3' => 'value'], $functionName($this->hashIterator, $callback));
    }

    /**
     * @dataProvider getAliases
     */
    public function testPassNonCallable($functionName)
    {
        $this->expectArgumentError(
            sprintf(
                "Argument 2 passed to %s() must be callable",
                $functionName
            )
         );
        $functionName($this->list, 'undefinedFunction');
    }

    /**
     * @dataProvider getAliases
     */
    public function testPassNoCollection($functionName)
    {
        $this->expectArgumentError(
            sprintf(
                '%s() expects parameter 1 to be array or instance of Traversable',
                $functionName
            )
        );
        $functionName('invalidCollection', 'strlen');
    }

    /**
     * @dataProvider getAliases
     */
    public function testExceptionIsThrownInArray($functionName)
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        $functionName($this->list, [$this, 'exception']);
    }

    /**
     * @dataProvider getAliases
     */
    public function testExceptionIsThrownInHash($functionName)
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        $functionName($this->hash, [$this, 'exception']);
    }

    /**
     * @dataProvider getAliases
     */
    public function testExceptionIsThrownInIterator($functionName)
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        $functionName($this->listIterator, [$this, 'exception']);
    }

    /**
     * @dataProvider getAliases
     */
    public function testExceptionIsThrownInHashIterator($functionName)
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        $functionName($this->hashIterator, [$this, 'exception']);
    }
}
