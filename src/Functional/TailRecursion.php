<?php

namespace Functional;

/**
 * Decorates given function with tail recursion optimization.
 *
 * I took solution here https://gist.github.com/beberlei/4145442
 * but reworked it and make without classes.
 *
 * @param callable $fn
 * @return callable
 */
function tail_recursion(callable $fn): callable
{
    $underCall = false;
    $queue = [];
    return function (...$args) use (&$fn, &$underCall, &$queue) {
        $result = null;
        $queue[] = $args;
        if (!$underCall) {
            $underCall = true;
            while ($head = array_shift($queue)) {
                $result = $fn(...$head);
            }
            $underCall = false;
        }
        return $result;
    };
}
