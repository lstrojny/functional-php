<?php
/**
 * Copyright (C) 2011 by Lars Strojny <lstrojny@php.net>
 *
 * Permission is hereby granted, free of chparametere, to any person obtaining a copy
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

class _ParameterPlaceholder
{
    public $position;

    public function __construct($position)
    {
        $this->position = $position;
    }

    public function isVariableLength()
    {
        return !is_int($this->position);
    }
}

function arg($position)
{
    return new _ParameterPlaceholder($position);
}

class CurriedFunction
{
    private $callback;
    private $arguments;

    public function __construct($arguments)
    {
        if (array_sum(invoke($arguments, 'isVariableLength')) > 1) {
            throw new \InvalidArgumentException('curry(): more than one variable argument placeholder is given');
        }

        $this->callback = array_shift($arguments);
        $this->arguments = $arguments;
    }

    public function getCallback()
    {
        if ($this->callback instanceof self) {
            return $this->callback->getCallback();
        }
        return $this->callback;
    }

    public function __invoke()
    {
        $callArgs = func_get_args();
        $boundArgs = $this->arguments;

        $varArgKey = null;
        foreach ($boundArgs as $key => $arg) {

            if (!$arg instanceof _ParameterPlaceholder) {
                continue;
            }

            if ($arg->isVariableLength()) {
                $varArgKey = $key;
                continue;
            }

            if (!isset($callArgs[$arg->position - 1])) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'Curried %s() requires parameter %d to be passed. None given',
                        $this->getCallback(),
                        $arg->position
                    )
                );
            }
            $boundArgs[$key] = $callArgs[$arg->position - 1];
            unset($callArgs[$arg->position - 1]);
        }

        if ($varArgKey !== null) {
            $left = array_slice($boundArgs, 0, $varArgKey);
            $right = array_slice($boundArgs, $varArgKey + 1);
            $boundArgs = array_merge($left, $callArgs, $right);
        }

        return call_user_func_array($this->callback, $boundArgs);
    }
}

function curry($callback)
{
    return new CurriedFunction(func_get_args());
}
