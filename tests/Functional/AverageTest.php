<?php
/**
 * Copyright (C) 2011-2015 by Lars Strojny <lstrojny@php.net>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the 'Software'), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
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
use function Functional\average;
use Traversable;

class AverageTest extends AbstractTestCase
{
    /** @var array */
    private $list2;

    /** @var Traversable */
    private $listIterator2;

    /** @var array */
    private $list3;

    /** @var Traversable */
    private $listIterator3;

    /** @var array */
    private $hash2;

    /** @var Traversable */
    private $hashIterator2;

    /** @var array */
    private $hash3;

    /** @var Traversable */
    private $hashIterator3;

    /** @before */
    public function createTestData()
    {
        $this->hash = ['f0' => 12, 'f1' => 2, 'f3' => true, 'f4' => false, 'f5' => 'str', 'f6' => [], 'f7' => new stdClass(), 'f8' => 1];
        $this->hashIterator = new ArrayIterator($this->hash);
        $this->list = array_values($this->hash);
        $this->listIterator = new ArrayIterator($this->list);

        $this->hash2 = ['f0' => 1.0, 'f1' => 0.5, 'f3' => true, 'f4' => false, 'f5' => 1];
        $this->hashIterator2 = new ArrayIterator($this->hash2);
        $this->list2 = array_values($this->hash2);
        $this->listIterator2 = new ArrayIterator($this->list2);

        $this->hash3 = ['f0' => [], 'f1' => new stdClass(), 'f2' => null, 'f3' => 'foo'];
        $this->hashIterator3 = new ArrayIterator($this->hash3);
        $this->list3 = array_values($this->hash3);
        $this->listIterator3 = new ArrayIterator($this->list3);
    }

    public function test()
    {
        $this->assertSame(5, average($this->hash));
        $this->assertSame(5, average($this->hashIterator));
        $this->assertSame(5, average($this->list));
        $this->assertSame(5, average($this->listIterator));

        $this->assertEquals(0.833333333, average($this->hash2), null, 0.001);
        $this->assertEquals(0.833333333, average($this->hashIterator2), null, 0.001);
        $this->assertEquals(0.833333333, average($this->list2), null, 0.001);
        $this->assertEquals(0.833333333, average($this->listIterator2), null, 0.001);

        $this->assertNull(average($this->hash3));
        $this->assertNull(average($this->hashIterator3));
        $this->assertNull(average($this->list3));
        $this->assertNull(average($this->listIterator3));
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\average() expects parameter 1 to be array or instance of Traversable');
        average('invalidCollection');
    }
}
