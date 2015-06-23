<?php
/**
 * Copyright (C) 2011-2015 by Lars Strojny <lstrojny@php.net>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
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
