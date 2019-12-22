<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional;

use Functional\Exceptions\InvalidArgumentException;

/**
 * Return a new function with the arguments partially applied
 *
 * Use Functional\…, Functional\…() or Functional\placeholder() as a placeholder
 *
 * @template TArgs
 * @template TArgsOrPlaceholder
 * @template TReturn
 * @param callable(...TArgsOrPlaceholder, ...TArgs): TReturn $callback
 * @param TArgsOrPlaceholder $arguments
 * @return callable(...TArgs): TReturn
 */
function partial_any(callable $callback, ...$arguments): callable
{
    return
    /**
     * @param TArgs $innerArguments
     */
    static function (...$innerArguments) use ($callback, $arguments) {
        $placeholder = …();

        foreach ($arguments as $position => &$argument) {
            if ($argument === $placeholder) {
                InvalidArgumentException::assertResolvablePlaceholder($innerArguments, $position);
                $argument = \array_shift($innerArguments);
            }
        }

        return $callback(...$arguments);
    };
}

function …(): string
{
    static $placeholder;

    if (!$placeholder) {
        $placeholder = \random_bytes(32);
    }

    return $placeholder;
}


function placeholder(): string
{
    return …();
}

// phpcs:disable
/** Define unicode ellipsis constant */
\define('Functional\\…', …());
// phpcs:enable
