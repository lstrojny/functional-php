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
namespace Functional;

use Functional\Exceptions\InvalidArgumentException;
use Traversable;

/**
 * @param Traversable|array $collection
 * @param int $size Desired size of the chunk
 * @param int $step Step with which to increment start of next chunk
 * @param bool $discardIncompleteChunk Discard the last chunk if it does not have the specified size.
 * @return array
 */
function chunk_every($collection, $size = 1, $step = 1, $discardIncompleteChunk = false)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    if ($collection instanceof Traversable) {
        $array = \iterator_to_array($collection);
    } else {
        $array = $collection;
    }

    $return = [];
    $previousChunk = [];
    for ($offset = 0, $max = \count($array); $offset <= $max; $offset += $step) {
        $currentChunk = \array_slice($array, $offset, $size);

        $wasPreviousChunkComplete = \count($previousChunk) === $size;
        $isCurrentChunkComplete = \count($currentChunk) === $size;

        if (($previousChunk === [] || $wasPreviousChunkComplete)
            && ($discardIncompleteChunk === false || $isCurrentChunkComplete)
            && !empty($currentChunk)
        ) {
            $return[] = $currentChunk;
        }

        $previousChunk = $currentChunk;
    }

    return $return;
}
