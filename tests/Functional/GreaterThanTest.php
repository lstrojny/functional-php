<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\greater_than;

class GreaterThanTest extends AbstractTestCase
{
    public function testGreaterThanComparison()
    {
        $fn = greater_than(2);

        $this->assertTrue($fn(3));
        $this->assertFalse($fn(2));
        $this->assertFalse($fn(1));
    }
}
