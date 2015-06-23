<?php
/**
 * Copyright (C) 2011-2015 by David Soria Parra <dsp@php.net>
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
use function Functional\flatten;

class FlattenTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->goodArray = [1, 2, 3, [4, 5, 6, [7, 8, 9]], 10, [11, [12, 13], 14], 15];
        $this->goodArray2 = [1 => 1, "foo" => "2", 3 => "3", ["foo" => 5]];
        $this->goodIterator = new ArrayIterator($this->goodArray);
        $this->goodIterator[3] = new ArrayIterator($this->goodIterator[3]);
        $this->goodIterator[5][1] = new ArrayIterator($this->goodIterator[5][1]);
    }

    public function test()
    {
        $this->assertSame(range(1, 15), flatten($this->goodArray));
        $this->assertSame(range(1, 15), flatten($this->goodIterator));
        $this->assertSame([1, "2", "3", 5], flatten($this->goodArray2));
        $this->assertEquals([new stdClass()], flatten([[new stdClass()]]));
        $this->assertSame([null, null], flatten([[null], null]));
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\flatten() expects parameter 1 to be array or instance of Traversable');
        flatten('invalidCollection');
    }
}
