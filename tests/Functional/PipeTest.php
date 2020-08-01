<?php
/**
 * Copyright (C) 2019 by Jesus Franco Martinez <tezcatl@fedoraproject.org>
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

use Apantle\FunPHP\Test\CustomClosure;
use function Functional\pipe;

class PipeTest extends AbstractTestCase
{
    /** @group tzkmx */
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
