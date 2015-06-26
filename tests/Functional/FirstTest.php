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
namespace Functional\Tests;

use ArrayIterator;
use Functional\Exceptions\InvalidArgumentException;
use function Functional\first;
use function Functional\head;

class FirstTest extends AbstractTestCase
{
    public function getAliases()
    {
        return [
            ['Functional\first'],
            ['Functional\head'],
        ];
    }

    public function setUp()
    {
        parent::setUp($this->getAliases());
        $this->list = ['first', 'second', 'third'];
        $this->listIterator = new ArrayIterator($this->list);
        $this->badArray = ['foo', 'bar', 'baz'];
        $this->badIterator = new ArrayIterator($this->badArray);
    }

    /**
     * @dataProvider getAliases
     */
    public function test($functionName)
    {
        $callback = function($v, $k, $collection) {
            InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);
            return $v == 'second' && $k == 1;
        };

        $this->assertSame('second', $functionName($this->list, $callback));
        $this->assertSame('second', $functionName($this->listIterator, $callback));
        $this->assertNull($functionName($this->badArray, $callback));
        $this->assertNull($functionName($this->badIterator, $callback));
    }

    /**
     * @dataProvider getAliases
     */
    public function testWithoutCallback($functionName)
    {
        $this->assertSame('first', $functionName($this->list));
        $this->assertSame('first', $functionName($this->list, null));
        $this->assertSame('first', $functionName($this->listIterator));
        $this->assertSame('first', $functionName($this->listIterator, null));
        $this->assertSame('foo', $functionName($this->badArray));
        $this->assertSame('foo', $functionName($this->badArray, null));
        $this->assertSame('foo', $functionName($this->badIterator));
        $this->assertSame('foo', $functionName($this->badIterator, null));
    }

    /**
     * @dataProvider getAliases
     */
    public function testPassNonCallable($functionName)
    {
        $this->expectArgumentError(
            sprintf(
                'Argument 2 passed to %s() must be callable',
                $functionName
            )
        );
        $functionName($this->list, 'undefinedFunction');
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
    public function testExceptionIsThrownInCollection($functionName)
    {
        $this->setExpectedException('DomainException', 'Callback exception');
        $functionName($this->listIterator, [$this, 'exception']);
    }

    /**
     * @dataProvider getAliases
     */
    public function testPassNoCollection($functionName)
    {
        $this->expectArgumentError(sprintf('%s() expects parameter 1 to be array or instance of Traversable', $functionName));
        $functionName('invalidCollection', 'strlen');
    }
}
