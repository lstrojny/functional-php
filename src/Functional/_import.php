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
    /** @var Functional\every() */
    require_once $basePath . 'Every.php';
    /** @var Functional\some() */
    require_once $basePath . 'Some.php';
    /** @var Functional\difference() */
    require_once $basePath . 'Difference.php';
    /** @var Functional\drop_first */
    require_once $basePath . 'DropFirst.php';
    /** @var Functional\drop_last */
    require_once $basePath . 'DropLast.php';
    /** @var Functional\each() */
    require_once $basePath . 'Each.php';
    /** @var Functional\first() */
    require_once $basePath . 'First.php';
    /** @var Functional\flatten() */
    require_once $basePath . 'Flatten.php';
    /** @var Functional\group() */
    require_once $basePath . 'Group.php';
    /** @var Functional\invoke() */
    require_once $basePath . 'Invoke.php';
    /** @var Functional\last() */
    require_once $basePath . 'Last.php';
    /** @var Functional\map() */
    require_once $basePath . 'Map.php';
    /** @var Functional\none() */
    require_once $basePath . 'None.php';
    /** @var Functional\partition() */
    require_once $basePath . 'Partition.php';
    /** @var Functional\pluck() */
    require_once $basePath . 'Pluck.php';
    /** @var Functional\product() */
    require_once $basePath . 'Product.php';
    /** @var Functional\ratio */
    require_once $basePath . 'Ratio.php';
    /** @var Functional\reduce_left() */
    require_once $basePath . 'ReduceLeft.php';
    /** @var Functional\reduce_right() */
    require_once $basePath . 'ReduceRight.php';
    /** @var Functional\reject() */
    require_once $basePath . 'Reject.php';
    /** @var Functional\select() */
    require_once $basePath . 'Select.php';
    /** @var Functional\sum() */
    require_once $basePath . 'Sum.php';
}
