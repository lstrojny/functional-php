<?php
/*
 * Copyright (C) 2011-2013 by Lars Strojny <lstrojny@php.net>
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

call_user_func(function() {
    static $symbols = array(
        'Functional\\Exceptions\\InvalidArgumentException',
        'Functional\\every',
        'Functional\\some',
        'Functional\\difference',
        'Functional\\drop_first',
        'Functional\\drop_last',
        'Functional\\each',
        'Functional\\first',
        'Functional\\filter',
        'Functional\\flatten',
        'Functional\\group',
        'Functional\\invoke',
        'Functional\\invoke_if',
        'Functional\\invoke_first',
        'Functional\\invoke_last',
        'Functional\\last',
        'Functional\\memoize',
        'Functional\\map',
        'Functional\\maximum',
        'Functional\\minimum',
        'Functional\\none',
        'Functional\\partition',
        'Functional\\pluck',
        'Functional\\product',
        'Functional\\ratio',
        'Functional\\unique',
        'Functional\\reduce_left',
        'Functional\\reduce_right',
        'Functional\\reject',
        'Functional\\select',
        'Functional\\sum',
        'Functional\\average',
        'Functional\\first_index_of',
        'Functional\\last_index_of',
        'Functional\\true',
        'Functional\\false',
        'Functional\\truthy',
        'Functional\\falsy',
        'Functional\\contains',
        'Functional\\zip',
        'Functional\\head',
        'Functional\\tail',
    );
    static $basePath = __DIR__;

    foreach ($symbols as $symbol) {

        if (function_exists($symbol) || class_exists($symbol, false) || interface_exists($symbol, false)) {
            continue;
        }

        $path = $basePath
            . DIRECTORY_SEPARATOR
            . str_replace(
                '\\',
                DIRECTORY_SEPARATOR,
                implode('', array_map('ucfirst', explode('_', substr($symbol, 11))))
            ) . '.php';

        if (!file_exists($path)) {
            trigger_error(sprintf('Could not load symbol "%s" in file "%s"', $symbol, $path));
        }

        include $path;
    }
});
