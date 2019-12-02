<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\compose;

class ComposeTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function test()
    {
        $input = range(0, 10);

        $plus2 = function ($x) {
            return $x + 2;
        };
        $times4 = function ($x) {
            return $x * 4;
        };
        $square = function ($x) {
            return $x * $x;
        };

        $composed = compose($plus2, $times4, $square);

        $composed_values = array_map(function ($x) use ($composed) {
            return $composed($x);
        }, $input);

        $manual_values = array_map(function ($x) use ($plus2, $times4, $square) {
            return $square($times4($plus2($x)));
        }, $input);

        $this->assertEquals($composed_values, $manual_values);
    }

    public function testPassNoFunctions()
    {
        $input = range(0, 10);

        $composed = compose();

        $composed_values = array_map(function ($x) use ($composed) {
            return $composed($x);
        }, $input);

        $this->assertEquals($composed_values, $input);
    }
}
