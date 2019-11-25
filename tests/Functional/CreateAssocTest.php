<?php
/**
 * Copyright (C) 2019 by Jesus Franco Martinez <tezcatl@fedoraproject.org>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace Functional\Tests;

use Functional\Functional;

use function Functional\create_assoc;
use function Apantle\HashMapper\hashMapper;

class CreateAssocTest extends AbstractTestCase
{
    public function testBasicCreateAssoc()
    {
        $input = new \DateTimeImmutable();

        $expected = [
          'targetA' => $input,
          'targetB' => $input
        ];

        $actual = create_assoc([
          'targetA' => Functional::id,
          'targetB' => Functional::id,
        ])($input);

        $this->assertEquals($expected, $actual);
        $this->assertEquals($input->format('U'), $actual['targetA']->format('U'));
        $this->assertEquals($input->format('U'), $actual['targetB']->format('U'));
    }

    public function testExpectsTameme()
    {
        $input = [ 1, 2, 3 ];

        $expected = [
          'targetA' => [ 4 => 1, 5 => 2, 6 => 3 ],
          'targetB' => [ 5 => 2 ]
        ];
        // phpcs:disable
        $tameme = new class extends \ArrayObject {};
        // phpcs:enable
        $_irrelevant = null;
        $actual = create_assoc([
          'targetA' => function ($member, $_irrelevant, $tameme) {
              $build = \array_reduce($member, function ($accum, $num) {
                  $accum[$num + 3] = $num;
                  return $accum;
              }, []);

              $tameme['prev'] = $build;
              return $build;
          },
          'targetB' => function ($member, $input, $tameme) {
            $prev = $tameme['prev'];
            $build = [];
            foreach ($prev as $key => $val) {
                if ($key % 2 !== 0) {
                    $build[$key] = $val;
                }
            }
            return $build;
          }
        ], $tameme)($input);

        $this->assertEquals($expected, $actual);
    }

    public function testReceivesOptionalArgument()
    {
        $input = [
          'vendor' => 'tzkmx',
          'utility' => 'unfold'
        ];

        $expected = [
          'vendorName' => 'tzkmx',
          'vendorLen' => 5,
          'serialized' => 'a:2:{s:6:"vendor";s:5:"tzkmx";s:7:"utility";s:6:"unfold";}',
          'utility' => 'unfold',
          'utilLen' => 6,
          'package' => [ 'tzkmx/unfold' => $input ]
        ];
        // phpcs:disable
        $tameme = new class extends \ArrayObject {};
        // phpcs:enable
        $actual = hashMapper([
          'vendor' => [ '...', create_assoc([
            'vendorName' => 'strval',
            'vendorLen' => 'strlen',
            'serialized' => function ($member, $hash, $tameme) {
                $tameme['name'] = $member;
                return \serialize($hash);
            }
          ], $tameme)
          ],
          'utility' => [ '...', create_assoc([
            'utility' => 'strval',
            'utilLen' => 'strlen',
            'package' => function ($member, $hash, $tameme) {
                $name = $tameme['name'];
                return [ "$name/$member" => $hash ];
            }
          ], $tameme)
          ]
        ])($input);

        $this->assertEquals($expected, $actual);
    }
}
