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
 * Return value itself, without any modifications.
 *
 * @param mixed $value
 * @return mixed
 * @no-named-arguments
 */
function id($value)
{
    return $value;
}
