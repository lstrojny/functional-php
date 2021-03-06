<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional;

/**
 * Concatenates zero or more strings
 *
 * @param string[] ...$strings
 * @return string
 * @no-named-arguments
 */
function concat(string ...$strings)
{
    return \implode('', $strings);
}
