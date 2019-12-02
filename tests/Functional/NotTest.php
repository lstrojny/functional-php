<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\greater_than as gt;
use function Functional\identical;
use function Functional\not;

class NotTest extends AbstractTestCase
{
    public function testNot()
    {
        $this->assertTrue(not(gt(2))(2));
        $this->assertFalse(not(gt(2))(3));
        $this->assertTrue(not(identical(2))('2'));
        $this->assertFalse(not(identical(2))(2));
    }
}
