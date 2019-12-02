<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use stdClass;

use function Functional\compare_object_hash_on;
use function Functional\const_function;

class CompareObjectHashOnTest extends AbstractTestCase
{
    public function testCompareValues()
    {
        $compare = compare_object_hash_on('strcmp');

        $this->assertSame(0, $compare($this, $this));
        $this->assertNotSame(0, $compare($this, new stdClass()));
    }

    public function testCompareWithReducer()
    {
        $compare = compare_object_hash_on('strcmp', const_function(new stdClass()));

        $this->assertSame(0, $compare($this, new stdClass()));
    }
}
