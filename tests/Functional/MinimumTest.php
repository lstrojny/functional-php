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

use ArrayIterator;
use stdClass;
use function Functional\minimum;

class MinimumTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->list = [1, "foo", 5.1, 5, "5.2", true, false, [], new stdClass()];
        $this->listIterator = new ArrayIterator($this->list);
        $this->hash = [
            'k1' => 1,
            'k2' => '5.2',
            'k3' => 5,
            'k4' => '5.1',
            'k5' => 10.2,
            'k6' => true,
            'k7' => [],
            'k8' => new stdClass(),
            'k9' => -10,
        ];
        $this->hashIterator = new ArrayIterator($this->hash);
    }

    public function testExtractingMinimumValue()
    {
        $this->assertEquals(1, minimum($this->list));
        $this->assertEquals(1, minimum($this->listIterator));
        $this->assertEquals(-10, minimum($this->hash));
        $this->assertEquals(-10, minimum($this->hashIterator));
    }

    public function testSpecialCaseNull()
    {
        $this->assertSame(-1, minimum([-1]));
    }

    public function testSpecialCaseSameValueDifferentTypes()
    {
        $this->assertSame(0, minimum([0, 1, 0.0, 1.0, "0", "1", "0.0", "1.0"]));
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\minimum() expects parameter 1 to be array or instance of Traversable');
        minimum('invalidCollection');
    }
}
