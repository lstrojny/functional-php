<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional;

/**
 * Concatenates zero or more strings
 *
 * @param string[] ...$strings
 * @return string
 */
function concat(string ...$strings)
{
    return \implode('', $strings);
}
