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
        $callback = function ($v, $k, $collection) {
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
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        $functionName($this->list, [$this, 'exception']);
    }

    /**
     * @dataProvider getAliases
     */
    public function testExceptionIsThrownInCollection($functionName)
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
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
