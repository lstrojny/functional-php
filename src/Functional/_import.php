<?php
/**
 * Copyright (C) 2011 by Lars Strojny <lstrojny@php.net>
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
if (!extension_loaded('functional')) {
    $basePath = __DIR__ . DIRECTORY_SEPARATOR;
    /** @var Functional\Exceptions\InvalidArgumentsException */
    require_once $basePath . 'Exceptions/InvalidArgumentException.php';
    /** @var Functional\all() */
    require_once $basePath . 'All.php';
    /** @var Functional\any() */
    require_once $basePath . 'Any.php';
    /** @var Functional\arg() */
    require_once $basePath . 'Arg.php';
    /** @var Functional\curry() */
    require_once $basePath . 'Curry.php';
    /** @var Functional\Currying\Curried */
    require_once $basePath . 'Currying/Curried.php';
    /** @var Functional\Currying\Placeholder */
    require_once $basePath . 'Currying/Placeholder.php';
    /** @var Functional\detect() */
    require_once $basePath . 'Detect.php';
    /** @var Functional\each() */
    require_once $basePath . 'Each.php';
    /** @var Functional\invoke() */
    require_once $basePath . 'Invoke.php';
    /** @var Functional\map() */
    require_once $basePath . 'Map.php';
    /** @var Functional\none() */
    require_once $basePath . 'None.php';
    /** @var Functional\pluck() */
    require_once $basePath . 'Pluck.php';
    /** @var Functional\reduceLeft() */
    require_once $basePath . 'ReduceLeft.php';
    /** @var Functional\reduceRight() */
    require_once $basePath . 'ReduceRight.php';
    /** @var Functional\reject() */
    require_once $basePath . 'Reject.php';
    /** @var Functional\select() */
    require_once $basePath . 'Select.php';
}
