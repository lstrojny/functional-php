<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\tap;

class TapTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testPassNonCallable()
    {
        $this->expectArgumentError('Argument 2 passed to Functional\tap() must be callable');
        tap('foo', 'undefinedFunction');
    }

    public function testTap()
    {
        $input = new \stdClass();
        $input->property = 'foo';

        $output = tap($input, function ($o) {
            $o->property = 'bar';
        });

        self::assertSame($input, $output);
        self::assertSame('bar', $input->property);
    }
}
