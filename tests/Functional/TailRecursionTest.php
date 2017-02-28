<?php

namespace Functional\Tests;

use PHPUnit\Framework\TestCase;

class TailRecursionTest extends TestCase
{
    public function testTailRecursion()
    {
        $fact = tailRecursion(function ($n, $acc = 1) use (&$fact) {
            if ($n == 0) {
                return $acc;
            }
            return $fact($n - 1, $acc * $n);
        });
        $this->assertEquals(3628800, $fact(10));
    }
}
