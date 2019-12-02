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

use function Functional\intersperse;

class IntersperseTest extends AbstractTestCase
{
    public function test()
    {
        $array = ['a', 'b', 'c'];
        $traversable = new ArrayIterator($array);
        
        $expected = ['a', '-', 'b', '-', 'c'];
        
        $this->assertSame($expected, intersperse($array, '-'));
        $this->assertSame($expected, intersperse($traversable, '-'));
    }

    public function testEmptyCollection()
    {
        $this->assertSame([], intersperse([], '-'));
        $this->assertSame([], intersperse(new ArrayIterator([]), '-'));
    }
    
    public function testIntersperseWithArray()
    {
        $this->assertSame(['a', ['-'], 'b'], intersperse(['a', 'b'], ['-']));
    }
    
    public function testPassNoCollection()
    {
        $this->expectArgumentError(
            'Functional\intersperse() expects parameter 1 to be array or ' .
            'instance of Traversable'
        );
        intersperse('invalidCollection', '-');
    }
}
