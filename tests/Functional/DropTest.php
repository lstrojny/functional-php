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

use function Functional\drop_last;
use function Functional\drop_first;

class DropTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp('Functional\drop_first', 'Functional\drop_last');
        $this->list = ['value1', 'value2', 'value3', 'value4'];
        $this->listIterator = new ArrayIterator($this->list);
        $this->hash = ['k1' => 'val1', 'k2' => 'val2', 'k3' => 'val3', 'k4' => 'val4'];
        $this->hashIterator = new ArrayIterator($this->hash);
    }

    public function test()
    {
        $fn = function ($v, $k, $collection) {
            InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
            $return = is_int($k) ? ($k != 2) : ($v[3] != 3);
            return $return;
        };
        $this->assertSame([0 => 'value1', 1 => 'value2'], drop_last($this->list, $fn));
        $this->assertSame([2 => 'value3', 3 => 'value4'], drop_first($this->list, $fn));
        $this->assertSame([2 => 'value3', 3 => 'value4'], drop_first($this->listIterator, $fn));
        $this->assertSame([0 => 'value1', 1 => 'value2'], drop_last($this->listIterator, $fn));
        $this->assertSame(['k3' => 'val3', 'k4' => 'val4'], drop_first($this->hash, $fn));
        $this->assertSame(['k1' => 'val1', 'k2' => 'val2'], drop_last($this->hash, $fn));
        $this->assertSame(['k3' => 'val3', 'k4' => 'val4'], drop_first($this->hashIterator, $fn));
        $this->assertSame(['k1' => 'val1', 'k2' => 'val2'], drop_last($this->hashIterator, $fn));
    }

    public static function getFunctions()
    {
        return [['Functional\drop_first'], ['Functional\drop_last']];
    }

    /** @dataProvider getFunctions */
    public function testExceptionIsThrownInArray($fn)
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        $fn($this->list, [$this, 'exception']);
    }

    /** @dataProvider getFunctions */
    public function testExceptionIsThrownInHash($fn)
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        $fn($this->hash, [$this, 'exception']);
    }

    /** @dataProvider getFunctions */
    public function testExceptionIsThrownInIterator($fn)
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        $fn($this->listIterator, [$this, 'exception']);
    }

    /** @dataProvider getFunctions */
    public function testExceptionIsThrownInHashIterator($fn)
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        $fn($this->hashIterator, [$this, 'exception']);
    }

    /** @dataProvider getFunctions */
    public function testPassNoCollection($fn)
    {
        $this->expectArgumentError($fn . '() expects parameter 1 to be array or instance of Traversable');
        $fn('invalidCollection', 'strlen');
    }

    /** @dataProvider getFunctions */
    public function testPassNonCallable($fn)
    {
        $this->expectArgumentError(sprintf('Argument 2 passed to %s() must be callable', $fn));
        $fn($this->list, 'undefinedFunction');
    }
}
