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

namespace Functional;

use Functional\Exceptions\InvalidArgumentException;

/**
 * Provides a functor that applies the functions passed at construction
 * from left to right, first function is able to admit several arguments
 * at once.
 *
 * @link https://github.com/lstrojny/functional-php/issues/141
 * @param callable[] ...$functions functions to be composed
 * @return callable
 */
function pipe(...$functions): callable
{
    return new Pipe($functions);
}

class Pipe
{
    /** @var callable[] */
    protected $callables;

    protected $carry;

    protected $pipeLength = 0;

    public function __construct(array $functions)
    {
        $this->pipeLength = count($functions);
        if ($this->pipeLength < 2) {
            throw new InvalidArgumentException(
                'You should pass at least 2 functions or functors to build a pipe'
            );
        }
        foreach ($functions as $index => $callable) {
            InvalidArgumentException::assertCallback($callable, 'pipe', $index + 1);
            $this->callables[] = $callable;
        }
    }

    public function __invoke()
    {
        $funArgs = \func_get_args();
        $this->carry = \call_user_func_array($this->callables[0], $funArgs);

        for ($index = 1; $index < $this->pipeLength; $index++) {
            $this->carry = \call_user_func(
                $this->callables[$index],
                $this->carry
            );
        }

        return $this->carry;
    }
}
