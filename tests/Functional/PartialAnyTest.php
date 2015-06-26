<?php
namespace Functional\Tests;

use Functional\Exceptions\InvalidArgumentException;
use function Functional\partial_any;
use function Functional\placeholder;
use function Functional\…;
use const Functional\…;

class PartialAnyTest extends AbstractPartialTestCase
{
    public function testBindWithPlaceholder()
    {
        $ratio = partial_any($this->ratio(), …(), 10);
        $this->assertSame(1, $ratio(10));
        $this->assertSame(2, $ratio(20));
    }

    public function testBindWithPlaceholder_Constant()
    {
        $context = hash_init('md2');
        $hash = partial_any('hash_update', $context, …());
        $hash('oh hi');
        $this->assertSame('6f24cbf6005b9bfc0176abbbe309f0d0', hash_final($context));
    }

    public function testBindWithMultiplePlaceholders()
    {
        $ratio = partial_any($this->ratio(), …(), 2, …());
        $this->assertSame(1, $ratio(10, 5));
        $this->assertSame(2, $ratio(20, 5));
    }

    public function testPlaceholderParameterPosition()
    {
        $substr = partial_any('substr', …(), 0, …());
        $this->assertSame('foo', $substr('foo', 3));
        $this->assertSame('fo', $substr('foo', 2));
        $this->assertSame('f', $substr('foo', 1));
    }

    public function testNoFurtherArgumentsResolvedAfterPlaceholder()
    {
        $firstNCharacters = partial_any('substr', …(), 0, …());
        $this->assertSame('f', $firstNCharacters('foo', 1, 100), 'Third parameter should be ignored');
    }

    public function testAliasForUnicodePlaceholder()
    {
        $this->assertSame(…(), placeholder());

        /* @see https://github.com/facebook/hhvm/issues/5548 */
        if (!defined('HHVM_VERSION')) {
            $this->assertSame(…, placeholder());
        }
    }

    public function testStringConversion()
    {
        $ratio = partial_any($this->ratio(), …(), 2);

        $this->setExpectedException(
            InvalidArgumentException::class,
            'Cannot resolve parameter placeholder at position 0. Parameter stack is empty.'
        );
        $ratio();
    }
}
