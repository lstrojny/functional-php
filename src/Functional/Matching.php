<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional;

use Functional\Exceptions\MatchException;

use function Functional\head;
use function Functional\tail;
use function Functional\if_else;

use const PHP_VERSION_ID;

/**
 * Performs an operation checking for the given conditions
 *
 * @param array $conditions the conditions to check against
 *
 * @return callable|null the function that calls the callable of the first truthy condition
 * @no-named-arguments
 */
function matching(array $conditions)
{
    MatchException::assert($conditions, __FUNCTION__);

    return static function ($value) use ($conditions) {
        if (empty($conditions)) {
            return null;
        }

        list($if, $then) = head($conditions);

        return if_else($if, $then, matching(tail($conditions)))($value);
    };
}


if (PHP_VERSION_ID < 80000 && !\function_exists('Functional\match')) {
    eval(<<<'ALIAS'
namespace Functional;

/** @no-named-arguments */
function match(array $conditions) {
    trigger_error('Functional\match() will be unavailable with PHP 8. Use Functional\matching() instead', E_USER_DEPRECATED);
    return matching($conditions);
}
ALIAS
    );
}
