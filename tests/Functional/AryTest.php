<?php

/**
 * @package   Functional-php
 * @author    Hugo Sales <hugo@fc.up.pt>
 * @copyright 2020 Hugo Sales
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\ary;

class AryTest extends AbstractTestCase
{

    public function test()
    {
        $this->assertSame(5, ary(function ($a = 0, $b = 0) { return $a + $b; }, 1)(5));
        $this->assertSame(5, ary(function ($a = 0, $b = 0, $c = 0) { return $a + $b + $c; }, 1)(5));
        $this->assertSame(6, ary(function ($a = 0, $b = 0, $c = 0) { return $a + $b + $c; }, -1)(6));
        $this->assertSame(7, ary(function ($a = 0, $b = 0, $c = 0) { return $a + $b + $c; }, 2)(5, 2));
        $this->assertNull(ary(function ($a = 0, $b = 0) { return $a + $b; }, 0)(5));
    }
}
