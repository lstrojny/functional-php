<?php
/**
 * Copyright (C) 2011-2015 by Lars Strojny <lstrojny@php.net>
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
 * Return a new function with the arguments partially applied
 *
 * Use Functional\…, Functional\…() or Functional\placeholder() as a placeholder
 *
 * @param callable $callback
 * @param array $arguments
 * @return callable
 */
function partial_any(callable $callback, ...$arguments)
{
    return function (...$innerArguments) use ($callback, $arguments) {
        $placeholder = …();

        foreach ($arguments as $position => &$argument) {
            if ($argument === $placeholder) {
                InvalidArgumentException::assertResolvablePlaceholder($innerArguments, $position);
                $argument = array_shift($innerArguments);
            }
        }

        return $callback(...$arguments);
    };
}

/* @see https://github.com/facebook/hhvm/issues/5549 */
if (defined('HHVM_VERSION')) {
    /** @return resource */
    function …()
    {
        static $resource;

        if (!$resource) {
            $resource = openssl_random_pseudo_bytes(128);
        }

        return $resource;
    }
} else {
    /** @return resource */
    function …()
    {
        static $resource;

        if (!$resource) {
            $resource = hash_init('gost');
        }

        return $resource;
    }
}


/** @return resource */
function placeholder()
{
    return …();
}

/** Define unicode ellipsis constant */
define('Functional\\…', …());
