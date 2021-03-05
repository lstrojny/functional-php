<?php

/**
 * Copyright (C) 2019, 2020 by Jesus Franco Martinez <tezcatl@fedoraproject.org>
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

use Functional\Exceptions\InvalidArgumentException;

use function Functional\pipe;

class PipeTest extends AbstractTestCase
{
    public function testPipeFunction()
    {
        $mockFirst = $this->getClosureMock(1, ['o', 'n', 'e'], 'one');
        $mockSecond = $this->getClosureMock(1, ['one'], 'one, two');
        $mockThird = $this->getClosureMock(1, ['one, two'], 'one, two, three');

        $result = pipe(
            $mockFirst,
            $mockSecond,
            $mockThird
        )('o', 'n', 'e');

        $this->assertEquals('one, two, three', $result);
    }

    public function testShouldNotAcceptSingleFunction()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('You should pass at least 2 functions or functors to build a pipe');
        pipe('strval')();
    }

    /** @dataProvider notQuiteFunctionsProvider */
    public function testExceptionNotCallable($maybeFun1, $maybeFun2, $expectedException)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedException);
        pipe($maybeFun1, $maybeFun2)();
    }

    public function notQuiteFunctionsProvider()
    {
        return [
          [
            'strval',
            '__not',
            'pipe() expects parameter 2 to be a valid callback, ' .
            'function \'__not\' not found or invalid function name'
          ],
          [
            'runabout',
            'intval',
            'pipe() expects parameter 1 to be a valid callback, ' .
            'function \'runabout\' not found or invalid function name'
          ]
        ];
    }

    private function getClosureMock(
        int $invocations,
        array $expectedArguments,
        $mustReturnValue
    ) {
        $mock = $this->getMockBuilder(CustomTestClosure::class)
          ->getMock();

        $argsArray = [];
        for ($index = 0; $index < count($expectedArguments); $index++) {
            $argsArray[] = $this->equalTo($expectedArguments[$index]);
        }

        $mock->expects($this->exactly($invocations))
            ->method('__invoke')
            ->withConsecutive($argsArray)
            ->willReturn($mustReturnValue);
        return $mock;
    }
}

class CustomTestClosure
{
    public function __invoke()
    {
    }
}
