<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use DomainException;
use Functional\Exceptions\InvalidArgumentException;
use Iterator;
use PHPUnit\Framework\Error\Deprecated;
use PHPUnit\Framework\TestCase;
use Traversable;
use TypeError;

class AbstractTestCase extends TestCase
{
    /** @var array */
    protected $list;

    /** @var Traversable */
    protected $listIterator;

    /** @var array */
    protected $hash;

    /** @var Traversable */
    protected $hashIterator;

    protected function expectArgumentError(string $message): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($message);
    }

    protected function expectCallableArgumentError(string $fn, int $position, string $actualType = 'string'): void
    {
        $this->expectException(TypeError::class);

        if (PHP_VERSION_ID < 80000) {
            $this->expectExceptionMessage(\sprintf('Argument %d passed to %s() must be callable', $position, $fn));
        } else {
            $this->expectExceptionMessageMatches(
                \sprintf(
                    '/^%s\(\): Argument \#%d( \(\$callback\))? must be of type \??callable, %s given.*/',
                    \preg_quote($fn, '/'),
                    $position,
                    $actualType
                )
            );
        }
    }

    public function exception(): void
    {
        if (\func_num_args() < 3) {
            throw new DomainException('Callback exception');
        }

        $args = \func_get_args();
        self::assertGreaterThanOrEqual(3, \count($args));
        throw new DomainException(\sprintf('Callback exception: %s', $args[1]));
    }

    protected function sequenceToArray(Iterator $sequence, int $limit): array
    {
        $values = [];
        $sequence->rewind();
        for ($a = 0; $a < $limit; $a++) {
            $values[] = $sequence->current();
            $sequence->next();
        }

        return $values;
    }

    public function expectDeprecation(): void
    {
        if (\method_exists(parent::class, __FUNCTION__)) {
            parent::expectDeprecation();
            return;
        }

        $this->expectException(Deprecated::class);
    }

    public function expectDeprecationMessage(string $message): void
    {
        if (\method_exists(parent::class, __FUNCTION__)) {
            parent::expectDeprecationMessage($message);
            return;
        }

        $this->expectExceptionMessage($message);
    }
}
