<?php
/**
 * Copyright (C) 2011-2015 by Lars Strojny <lstrojny@php.net>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the 'Software'), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace Functional;

use ArrayIterator;

class FirstTest extends AbstractTestCase
{
    function getAliases()
    {
        return array(
            array('Functional\first'),
            array('Functional\head'),
        );
    }

    function setUp()
    {
        parent::setUp($this->getAliases());
        $this->array = array('first', 'second', 'third');
        $this->iterator = new ArrayIterator($this->array);
        $this->badArray = array('foo', 'bar', 'baz');
        $this->badIterator = new ArrayIterator($this->badArray);
    }

    /**
     * @dataProvider getAliases
     */
    function test($functionName)
    {
        $callback = function($v, $k, $collection) {
            Exceptions\InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);
            return $v == 'second' && $k == 1;
        };

        $this->assertSame('second', $functionName($this->array, $callback));
        $this->assertSame('second', $functionName($this->iterator, $callback));
        $this->assertNull($functionName($this->badArray, $callback));
        $this->assertNull($functionName($this->badIterator, $callback));
    }

    /**
     * @dataProvider getAliases
     */
    function testWithoutCallback($functionName)
    {
        $this->assertSame('first', $functionName($this->array));
        $this->assertSame('first', $functionName($this->array, null));
        $this->assertSame('first', $functionName($this->iterator));
        $this->assertSame('first', $functionName($this->iterator, null));
        $this->assertSame('foo', $functionName($this->badArray));
        $this->assertSame('foo', $functionName($this->badArray, null));
        $this->assertSame('foo', $functionName($this->badIterator));
        $this->assertSame('foo', $functionName($this->badIterator, null));
    }

    /**
     * @dataProvider getAliases
     */
    function testPassNonCallable($functionName)
    {
        $this->expectArgumentError(
            sprintf(
                '%s() expects parameter 2 to be a valid callback, function \'undefinedFunction\' not found or invalid function name',
                $functionName
            )
        );
        $functionName($this->array, 'undefinedFunction');
    }

    /**
     * @dataProvider getAliases
     */
    function testExceptionIsThrownInArray($functionName)
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        $functionName($this->array, array($this, 'exception'));
    }

    /**
     * @dataProvider getAliases
     */
    function testExceptionIsThrownInCollection($functionName)
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        $functionName($this->iterator, array($this, 'exception'));
    }

    /**
     * @dataProvider getAliases
     */
    function testPassNoCollection($functionName)
    {
        $this->expectArgumentError(sprintf('%s() expects parameter 1 to be array or instance of Traversable', $functionName));
        $functionName('invalidCollection', 'strlen');
    }
}
