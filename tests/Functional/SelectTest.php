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
        $callback = function ($v, $k, $collection) {
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
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        $functionName($this->list, [$this, 'exception']);
    }

    /**
     * @dataProvider getAliases
     */
    public function testExceptionIsThrownInHash($functionName)
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        $functionName($this->hash, [$this, 'exception']);
    }

    /**
     * @dataProvider getAliases
     */
    public function testExceptionIsThrownInIterator($functionName)
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        $functionName($this->listIterator, [$this, 'exception']);
    }

    /**
     * @dataProvider getAliases
     */
    public function testExceptionIsThrownInHashIterator($functionName)
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        $functionName($this->hashIterator, [$this, 'exception']);
    }
}
