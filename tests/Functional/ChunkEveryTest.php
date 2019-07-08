<?php
/**
 * Copyright (C) 2019 by Bas Bloembergen <basbloembergen@gmail.com>
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

use function Functional\chunk_every;

class ChunkEveryTest extends AbstractTestCase
{
    /**
     * @dataProvider getData
     */
    public function testChunking(array $expected, array $input, int $size, int $step, bool $discardIncompleteChunk)
    {
        $this->assertSame(
            $expected,
            chunk_every($input, $size, $step, $discardIncompleteChunk)
        );
    }

    public static function getData()
    {
        return [
            'single_size_single_step_discard_last_chunk' => [
                [[1], [2], [3], [4], [5], [6], [7], [8], [9], [10]],
                range(1, 10),
                1,
                1,
                true
            ],
            'single_size_single_step_keep_last_chunk' => [
                [[1], [2], [3], [4], [5], [6], [7], [8], [9], [10]],
                range(1, 10),
                1,
                1,
                false
            ],
            'single_size_two_step_discard_last_chunk' => [
                [[1], [3], [5], [7], [9]],
                range(1, 10),
                1,
                2,
                true
            ],
            'single_size_two_step_keep_last_chunk' => [
                [[1], [3], [5], [7], [9]],
                range(1, 10),
                1,
                2,
                false
            ],
            'double_size_one_step_discard_last_chunk' => [
                [[1, 2], [2, 3], [3, 4], [4, 5], [5, 6], [6, 7], [7, 8], [8, 9], [9, 10]],
                range(1, 10),
                2,
                1,
                true
            ],
            'double_size_one_step_keep_last_chunk' => [
                [[1, 2], [2, 3], [3, 4], [4, 5], [5, 6], [6, 7], [7, 8], [8, 9], [9, 10], [10]],
                range(1, 10),
                2,
                1,
                false
            ],
            'double_size_two_step_discard_last_chunk' => [
                [[1, 2], [3, 4], [5, 6], [7, 8], [9, 10]],
                range(1, 10),
                2,
                2,
                false
            ],
            'incomplete_first_chunk_discard_last' => [
                [],
                range(1, 10),
                11,
                1,
                true
            ],
            'incomplete_first_chunk_keep_last' => [
                [range(1, 10)],
                range(1, 10),
                11,
                1,
                false
            ],
        ];
    }
}
