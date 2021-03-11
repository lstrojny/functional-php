<?php
/**
 * Copyright (C) 2019 by Sergei Kolesnikov <win0err@gmail.com>
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

use function Functional\prop_or;

class PropOrTest extends AbstractTestCase
{
    const DEFAULT = 'amazing default value';

    /**
     * @dataProvider provider
     *
     * @param $expected
     * @param $key
     * @param $item
     */
    public function testPropOr($expected, $key, $item)
    {
        $this->assertEquals($expected, prop_or(static::DEFAULT, $key, $item));
    }

    public function testPropOrException()
    {
        $this->expectExceptionMessage('Ha ha!');

        $object = new class {
            public function __isset($propertyName)
            {
                throw new \RuntimeException('Ha ha!');
            }
        };

        prop_or(static::DEFAULT, 'any', $object);
    }

    public function provider()
    {
        $object = new class {
            private $a = 5;
            public $b = 5;
        };

        $objectTestGetter = new class {
            public function __get($propertyName)
            {
                throw new \RuntimeException($propertyName);
            }
        };

        return [
            ['42', 'a', (object)['a' => '42', 'b' => '69']],
            ['42', 'a', ['a' => '42', 'b' => '69']],
            ['69', 1, ['42', '69']],
            [static::DEFAULT, 'a', ['b' => '69']],
            [static::DEFAULT, 'a', $object],
            [5, 'b', $object],
            [static::DEFAULT, 'anyField', $objectTestGetter],
            ['a', 0, \SplFixedArray::fromArray(['a', 'b', 'c'])],
            [static::DEFAULT, 'nonExisting', new \ArrayObject(['a', 'b', 'c'])],
            ['a', 'first', new \ArrayObject(['first' => 'a', 'b', 'c'])],
        ];
    }
}
