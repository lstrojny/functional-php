<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\sequence_linear;

class SequenceLinearTest extends AbstractTestCase
{
    public function testLinearIncrements()
    {
        $sequence = sequence_linear(0, 1);

        $values = $this->sequenceToArray($sequence, 10);

        $this->assertSame([0, 1, 2, 3, 4, 5, 6, 7, 8, 9], $values);
    }

    public function testLinearNegativeIncrements()
    {
        $sequence = sequence_linear(0, -1);

        $values = $this->sequenceToArray($sequence, 10);

        $this->assertSame([0, -1, -2, -3, -4, -5, -6, -7, -8, -9], $values);
    }

    public function testArgumentMustBePositiveInteger()
    {
        $this->expectArgumentError(
            'Functional\sequence_linear() expects parameter 1 to be an integer greater than or equal to 0'
        );
        sequence_linear(-1, 1);
    }

    public function testAmountArgumentMustBeInteger()
    {
        $this->expectArgumentError(
            'Functional\sequence_linear() expects parameter 2 to be integer'
        );
        sequence_linear(0, 1.1);
    }
}
