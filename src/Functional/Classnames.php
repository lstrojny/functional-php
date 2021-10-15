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
 * Returns classnames conditionally
 *  @param array $arr the conditions to check against
 *  @param string $permanent_classes
 *  @return string a conditional version of the given function
 *  @no-named-arguments
 */

function classnames(array $arr = array(), string $permanent_classes = '') 
{
    if(!is_array($arr)) return '';
    $classes = ' ' . $permanent_classes;
    foreach ($arr as $key => $value) {
        if((isset($value) && strlen($value)) || $value == true)
            $classes .= ' ' . $key;
    }
    return trim($classes);
}