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

use Functional\Exceptions\InvalidArgumentException;
use ArrayIterator;

function option($x = null)
{
    return null === $x ? Option::zero() : Option::lift($x);
}

/**
 * Similar to Scala Option or Haskell Maybe
 */
abstract class Option implements \IteratorAggregate, Semigroup, Monoid, Functor, Monad
{
    /**
     * Option catamorphism
     *
     * @param callable $callbackSome
     * @param callable $callbackNone
     * @return mixed
     */
    public abstract function fold($callbackSome, $callbackNone);

    /**
     * @return bool
     */
    public abstract function isEmpty();

    /**
     * @return bool
     */
    public final function isNotEmpty()
    {
        return !$this->isEmpty();
    }

    /**
     * @param mixed $y
     *
     * @return mixed
     */
    public abstract function getOrElse($y);

    /**
     * Monoid zero
     *
     * @return None
     */
    public static final function zero()
    {
        return new None();
    }

    /**
     * Monad lift (aka return or pure)
     *
     * @return Some
     */
    public static final function lift($x)
    {
        return new Some($x);
    }

    /**
     * @return array
     */
    public abstract function toArray();

    /**
     * @implements IteratorAggregate
     * @return array
     */
    public final function getIterator()
    {
        return new ArrayIterator($this->toArray());
    }
}

final class Some extends Option
{
    /* final */ private $x;

    public final function __construct($x)
    {
        $this->x = $x;
    }

    public final function get()
    {
        return $this->x;
    }

    public final function isEmpty()
    {
        return false;
    }

    public final function getOrElse($y)
    {
        return $this->x;
    }

    public final function append($o, $callback = 'Functional\\semigroupDefaultAppend')
    {
        InvalidArgumentException::assertCallback($callback, __FUNCTION__, 1);

        $x = $this->x;
        return $o->isEmpty()
            ? $this
            : $o->map(function($y) use ($callback, $x) {
                return $callback($x, $y);
            });
    }

    public final function fold($callbackSome, $callbackNone)
    {
        InvalidArgumentException::assertCallback($callbackSome, __FUNCTION__, 1);

        return $callbackSome($this->x);
    }

    public final function map($callback)
    {
        InvalidArgumentException::assertCallback($callback, __FUNCTION__, 1);

        return new Some(call_user_func($callback, $this->x));
    }

    public final function bind($callback)
    {
        InvalidArgumentException::assertCallback($callback, __FUNCTION__, 1);

        return call_user_func($callback, $this->x);
    }

    public final function toArray()
    {
        return array($this->x);
    }
}

final class None extends Option
{
    public final function isEmpty()
    {
        return true;
    }

    public final function append($o, $callback = 'Functional\\append')
    {
        return $o;
    }

    public final function getOrElse($y)
    {
        return $y;
    }

    public final function fold($callbackSome, $callbackNone)
    {
        InvalidArgumentException::assertCallback($callbackNone, __FUNCTION__, 1);

        return $callbackNone();
    }

    public final function map($callback)
    {
        return $this;
    }

    public final function bind($callback)
    {
        return $this;
    }

    public final function toArray()
    {
        return array();
    }
}
