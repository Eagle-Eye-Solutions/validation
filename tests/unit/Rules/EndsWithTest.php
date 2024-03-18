<?php

/*
 * This file is part of Respect/Validation.
 *
 * (c) Alexandre Gomes Gaigalas <alexandre@gaigalas.net>
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

namespace Respect\Validation\Test\Rules;

use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\EndsWithException;
use Respect\Validation\Rules\EndsWith;

/**
 * @group  rule
 * @covers EndsWith
 * @covers EndsWithException
 */
class EndsWithTest extends TestCase
{
    /**
     * @dataProvider providerForEndsWith
     * @throws \Exception
     */
    public function testStringsEndingWithExpectedValueShouldPass($start, $input): void
    {
        $v = new EndsWith($start);
        static::assertTrue($v->__invoke($input));
        static::assertTrue($v->check($input));
        static::assertTrue($v->assert($input));
    }

    /**
     * @dataProvider providerForNotEndsWith
     * @throws \Exception
     */
    public function testStringsNotEndingWithExpectedValueShouldNotPass($start, $input, $caseSensitive = false): void
    {
        $this->expectException(EndsWithException::class);
        $v = new EndsWith($start, $caseSensitive);
        static::assertFalse($v->__invoke($input));
        static::assertFalse($v->assert($input));
    }

    public static function providerForEndsWith(): array
    {
        return [
            ['foo', ['bar', 'foo']],
            ['foo', 'barbazFOO'],
            ['foo', 'barbazfoo'],
            ['foo', 'foobazfoo'],
            ['1', [2, 3, 1]],
            ['1', [2, 3, '1'], true],
        ];
    }

    public static function providerForNotEndsWith(): array
    {
        return [
            ['foo', '0'],
            ['bat', ['bar', 'foo']],
            ['foo', 'barfaabaz'],
            ['foo', 'barbazFOO', true],
            ['foo', 'faabarbaz'],
            ['foo', 'baabazfaa'],
            ['foo', 'baafoofaa'],
            ['1', [1, '1', 3], true],
        ];
    }
}
