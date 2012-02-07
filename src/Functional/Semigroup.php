<?php
/**
 * Copyright (C) 2011 - 2012 by Lars Strojny <lstrojny@php.net>
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

use Exceptions\InvalidArgumentException;

interface Semigroup
{
    /**
     * Semigroup[A] => Semigroup[A] => Semigroup[A]
     */
    public function append($m, $callback);
}

/**
 * Default append operation for Semigroup
 * $x and $y are assumed to be of the same type
 *
 * @returns value with type of $x
 */
function semigroupDefaultAppend($x, $y)
{
    if ($x instanceof Semigroup) {
        return $x->append($y);
    }
    if (is_string($x)) return $x . (string) $y;
    if (is_numeric($x)) return $x + $y;
    if (is_bool($x)) return $x && $y;
    if (is_array($x)) {
        array_push($x, $y);
        return $x;
    }

    throw new InvalidArgumentException('Semigroup defaultAppend is not applicable on ' . $x);
}
