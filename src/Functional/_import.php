<?php
/*
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
if (!extension_loaded('functional')) {
    $basePath = __DIR__ . DIRECTORY_SEPARATOR;
    require_once $basePath . 'Exceptions/InvalidArgumentException.php';
    require_once $basePath . 'Every.php';
    require_once $basePath . 'Some.php';
    require_once $basePath . 'Difference.php';
    require_once $basePath . 'DropFirst.php';
    require_once $basePath . 'DropLast.php';
    require_once $basePath . 'Each.php';
    require_once $basePath . 'First.php';
    require_once $basePath . 'Flatten.php';
    require_once $basePath . 'Group.php';
    require_once $basePath . 'Invoke.php';
    require_once $basePath . 'InvokeFirst.php';
    require_once $basePath . 'InvokeLast.php';
    require_once $basePath . 'Last.php';
    require_once $basePath . 'Map.php';
    require_once $basePath . 'Maximum.php';
    require_once $basePath . 'Minimum.php';
    require_once $basePath . 'None.php';
    require_once $basePath . 'Partition.php';
    require_once $basePath . 'Pluck.php';
    require_once $basePath . 'Product.php';
    require_once $basePath . 'Ratio.php';
    require_once $basePath . 'Unique.php';
    require_once $basePath . 'ReduceLeft.php';
    require_once $basePath . 'ReduceRight.php';
    require_once $basePath . 'Reject.php';
    require_once $basePath . 'Select.php';
    require_once $basePath . 'Sum.php';
    require_once $basePath . 'Average.php';
    require_once $basePath . 'FirstIndexOf.php';
    require_once $basePath . 'LastIndexOf.php';
    require_once $basePath . 'True.php';
    require_once $basePath . 'False.php';
    require_once $basePath . 'Truthy.php';
    require_once $basePath . 'Falsy.php';
    require_once $basePath . 'Contains.php';
    require_once $basePath . 'Zip.php';
    require_once $basePath . 'Head.php';
    require_once $basePath . 'Tail.php';
}
