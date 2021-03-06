<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use Functional\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;

use function Functional\repeat;

class Repeated
{
    public function foo(): void
    {
    }
}

class RepeatTest extends AbstractTestCase
{
    /** @var Repeated|MockObject */
    private $repeated;

    public function setUp(): void
    {
        parent::setUp();
        $this->repeated = $this->createMock(Repeated::class);
    }

    public function test(): void
    {
        $this->repeated
            ->expects(self::exactly(10))
            ->method('foo');

        repeat([$this->repeated, 'foo'])(10);
    }

    public function testNegativeRepeatedTimes(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Functional\{closure}() expects parameter 1 to be positive integer, negative integer given'
        );

        repeat([$this->repeated, 'foo'])(-1);
    }
}
