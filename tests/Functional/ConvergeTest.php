<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\converge;

class ConvergeTest extends AbstractTestCase
{
    public function testCallablesAsStrings(): void
    {
        $average = converge(function ($dividend, $divisor) {
            return $dividend / $divisor;
        }, ['array_sum', 'count']);
        self::assertEquals(2.5, $average([1, 2, 3, 4]));
    }

    public function testCallablesAsAnonymous(): void
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

        self::assertSame(
            'FUNCTIONAL PROGRAMMINGfunctional programming',
            $strangeFunction('Functional Programming')
        );
    }
}
