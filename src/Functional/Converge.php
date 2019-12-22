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
 * Accepts a converging function and a list of branching functions and returns a new function.
 *
 * The results of each branching function are passed as arguments
 * to the converging function to produce the return value.
 *
 * @template TArg
 * @template TBranchReturn
 * @template TConvergeReturn
 * @template TConvergeFn of callable(...TArg): TBranchReturn
 * @param callable(...TBranchReturn): TConvergeReturn $convergingFunction Will be invoked with the return values of all branching functions as its arguments
 * @param list<TConvergeFn> $branchingFunctions A list of functions
 * @return callable(...TArg): TConvergeReturn
 */
function converge($convergingFunction, array $branchingFunctions): callable
{
    return
    /**
     * @param TArg $values
     * @return TConvergeReturn
     */
    static function (...$values) use ($convergingFunction, $branchingFunctions) {
        $result = [];

        foreach ($branchingFunctions as $branchingFunction) {
            $result[] = $branchingFunction(...$values);
        }

        return $convergingFunction(...$result);
    };
}
