<?php
/**
 * Copyright (C) 2011-2017 by Gilles Crettenand <gilles@crettenand.info>
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

use function Functional\curry_n;
use function Functional\invoker;

use DateTime;

function add($a, $b, $c, $d) {
    return $a + $b + $c + $d;
}

class Adder
{
    public static function static_add($a, $b, $c, $d)
    {
        return add($a, $b, $c, $d);
    }

    public function add($a, $b, $c, $d)
    {
        return static::static_add($a, $b, $c, $d);
    }

    public function __invoke($a, $b, $c, $d)
    {
        return static::static_add($a, $b, $c, $d);
    }
}

class CurryNTest extends AbstractPartialTestCase
{
    protected function getCurryiedCallable($callback, $params, $required)
    {
        return curry_n(count($params), $callback);
    }

    /**
     * @dataProvider callbacks
     */
    public function testCallbackTypes($callback, $params, $expected, $required, $transformer = null)
    {
        if (is_null($transformer)) {
            $transformer = 'Functional\id';
        }

        $curryied = $this->getCurryiedCallable($callback, $params, $required);

        $this->assertEquals($transformer($expected), $transformer(call_user_func_array($curryied, $params)));

        $length = count($params);
        for($i = 0; $i < $length; ++$i) {
            $p = array_shift($params);

            $curryied = $curryied($p);

            if (count($params) > 0) {
                $this->assertTrue(is_callable($curryied));
                $this->assertEquals($transformer($expected), $transformer(call_user_func_array($curryied, $params)));
            } else {
                $this->assertEquals($transformer($expected), $transformer($curryied));
            }
        }
    }

    public function callbacks()
    {
        $dt = new DateTime();
        $dt2 = clone $dt;

        $dateFormat = invoker('format', [DateTime::ATOM]);

        return [
            ['Functional\Tests\add', [2, 4, 6, 8], 20, true],
            [['Functional\Tests\Adder', 'static_add'], [2, 4, 6, 8], 20, true],
            ['Functional\Tests\Adder::static_add', [2, 4, 6, 8], 20, true],
            [new Adder(), [2, 4, 6, 8], 20, true],
            [[new Adder(), 'add'], [2, 4, 6, 8], 20, true],
            [[new Adder(), 'static_add'], [2, 4, 6, 8], 20, true],

            ['number_format', [1.234, 2, ',', '\''], '1,23', false],
            [['DateTime', 'createFromFormat'], [DateTime::ATOM, $dt->format(DateTime::ATOM)], $dt, true, $dateFormat],
            ['DateTime::createFromFormat', [DateTime::ATOM, $dt->format(DateTime::ATOM)], $dt, true, $dateFormat],
            [[new DateTime, 'createFromFormat'], [DateTime::ATOM, $dt->format(DateTime::ATOM)], $dt, true, $dateFormat],
            [[new DateTime, 'setTime'], [10, 10], $dt2->setTime(10, 10), true, $dateFormat],
        ];
    }
}
