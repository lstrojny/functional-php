<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\capture;

class CaptureTest extends AbstractTestCase
{
    public function testCaptureReturnValue()
    {
        $fn = capture(
            function (...$args) {
                return $args;
            },
            $result
        );

        $this->assertSame([1, 2], $fn(1, 2));
        $this->assertSame([1, 2], $result);
    }
}
