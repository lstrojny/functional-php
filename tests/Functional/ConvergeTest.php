<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\converge;

class ConvergeTest extends AbstractTestCase
{
    public function testCallablesAsStrings()
    {
        $average = converge('Functional\Tests\div', ['array_sum', 'count']);
        $this->assertEquals(2.5, $average([1, 2, 3, 4]));
    }

    public function testCallablesAsAnonymous()
    {
        $strangeFunction = converge(
            function ($u, $l) {
                return $u . $l;
            },
            [
                'strtoupper',
                'strtolower',
            ]
        );

        $this->assertSame(
            'FUNCTIONAL PROGRAMMINGfunctional programming',
            $strangeFunction('Functional Programming')
        );
    }
}

function div($dividend, $divisor)
{
    return $dividend / $divisor;
}
