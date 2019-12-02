<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\equal;

class EqualTest extends AbstractTestCase
{
    public function testEqual()
    {
        $this->assertTrue(equal(2)(2));
        $this->assertFalse(equal(2)(3));
        $this->assertFalse(equal(3)(2));
        $this->assertTrue(equal(3)(3));
    }
}
