<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use ArrayIterator;
use Functional\Exceptions\InvalidArgumentException;

use function Functional\frequencies;

class FrequenciesTest extends AbstractTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->list = ['apple', 'banana', 'apple', 'cherry', 'banana', 'apple'];
        $this->listIterator = new ArrayIterator($this->list);
        $this->hash = ['a' => 'one', 'b' => 'two', 'c' => 'one', 'd' => 'two', 'e' => 'one'];
        $this->hashIterator = new ArrayIterator($this->hash);
    }

    public function test(): void
    {
        $expected = ['apple' => 3, 'banana' => 2, 'cherry' => 1];
        self::assertSame($expected, frequencies($this->list));
        self::assertSame($expected, frequencies($this->listIterator));

        $expectedHash = ['one' => 3, 'two' => 2];
        self::assertSame($expectedHash, frequencies($this->hash));
        self::assertSame($expectedHash, frequencies($this->hashIterator));
    }

    public function testWithCallback(): void
    {
        $fn = function ($v, $k, $collection) {
            InvalidArgumentException::assertCollection($collection, __FUNCTION__, 3);
            return \strlen($v);
        };

        $expected = [5 => 3, 6 => 3];
        self::assertSame($expected, frequencies($this->list, $fn));
        self::assertSame($expected, frequencies($this->listIterator, $fn));

        $expectedHash = [3 => 5];
        self::assertSame($expectedHash, frequencies($this->hash, $fn));
        self::assertSame($expectedHash, frequencies($this->hashIterator, $fn));
    }

    public function testEmptyCollection(): void
    {
        self::assertSame([], frequencies([]));
        self::assertSame([], frequencies(new ArrayIterator([])));
    }

    public function testNumericValues(): void
    {
        $list = [1, 2, 1, 3, 2, 1];
        $expected = [1 => 3, 2 => 2, 3 => 1];
        self::assertSame($expected, frequencies($list));
        self::assertSame($expected, frequencies(new ArrayIterator($list)));
    }

    public function testExceptionIsThrownWhenCallbackReturnsInvalidKey(): void
    {
        $invalidTypes = [
            'resource' => \stream_context_create(),
            'object'   => new \stdClass(),
            'array'    => []
        ];

        foreach ($invalidTypes as $type => $value) {
            $fn = function () use ($value) {
                return $value;
            };
            try {
                frequencies(['v1'], $fn);
                self::fail(\sprintf('Error expected for array key type "%s"', $type));
            } catch (\Exception $e) {
                self::assertSame(
                    \sprintf(
                        'Functional\frequencies(): callback returned invalid array key of type "%s". Expected NULL, string, integer, double or boolean',
                        $type
                    ),
                    $e->getMessage()
                );
            }
        }
    }

    public function testPassNoCollection(): void
    {
        $this->expectArgumentError('Functional\frequencies() expects parameter 1 to be array or instance of Traversable');
        frequencies('invalidCollection');
    }

    public function testPassNonCallable(): void
    {
        $this->expectCallableArgumentError('Functional\frequencies', 2);
        frequencies($this->list, 'undefinedFunction');
    }
}
