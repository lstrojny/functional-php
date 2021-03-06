<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use function Functional\sequence_exponential;

class SequenceExponentialTest extends AbstractTestCase
{
    public function testExponentialIncrementsWith100PercentGrowth(): void
    {
        $sequence = sequence_exponential(1, 100);

        $values = $this->sequenceToArray($sequence, 10);

        self::assertSame([1, 2, 4, 8, 16, 32, 64, 128, 256, 512], $values);
    }

    public function testExponentialIncrementsWith50PercentGrowth(): void
    {
        $sequence = sequence_exponential(1, 50);

        $values = $this->sequenceToArray($sequence, 10);

        self::assertSame([1, 2, 2, 3, 5, 8, 11, 17, 26, 38], $values);
    }

    public function testStartArgumentMustBePositiveInteger(): void
    {
        $this->expectArgumentError(
            'Functional\sequence_exponential() expects parameter 1 to be an integer greater than or equal to 1'
        );
        sequence_exponential(-1, 1);
    }

    public function testGrowthArgumentMustBePositiveInteger(): void
    {
        $this->expectArgumentError(
            'Functional\sequence_exponential() expects parameter 2 to be an integer greater than or equal to 1'
        );
        sequence_exponential(1, 0);
    }

    public function testGrowthArgumentMustBePositiveIntegerLessThan100(): void
    {
        $this->expectArgumentError(
            'Functional\sequence_exponential() expects parameter 2 to be an integer less than or equal to 100'
        );
        sequence_exponential(1, 101);
    }
}
