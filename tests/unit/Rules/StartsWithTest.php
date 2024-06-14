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
use Respect\Validation\Exceptions\StartsWithException;
use Respect\Validation\Rules\StartsWith;

/**
 * @group  rule
 * @covers StartsWith
 * @covers StartsWithException
 */
class StartsWithTest extends TestCase
{
    /**
     * @dataProvider providerForStartsWith
     */
    public function testStartsWith($start, $input): void
    {
        $v = new StartsWith($start);
        static::assertTrue($v->__invoke($input));
        static::assertTrue($v->check($input));
        static::assertTrue($v->assert($input));
    }

    /**
     * @dataProvider providerForNotStartsWith
     *
     */
    public function testNotStartsWith($start, $input, $caseSensitive = false)
    {
        $this->expectException(StartsWithException::class);
        $v = new StartsWith($start, $caseSensitive);
        static::assertFalse($v->__invoke($input));
        static::assertFalse($v->assert($input));
    }

    public static function providerForStartsWith()
    {
        return [
            ['foo', ['foo', 'bar']],
            ['foo', 'FOObarbaz'],
            ['foo', 'foobarbaz'],
            ['foo', 'foobazfoo'],
            ['1', [1, 2, 3]],
            ['1', ['1', 2, 3], true],
        ];
    }

    public static function providerForNotStartsWith()
    {
        return [
            ['foo', ''],
            ['bat', ['foo', 'bar']],
            ['foo', 'barfaabaz'],
            ['foo', 'FOObarbaz', true],
            ['foo', 'faabarbaz'],
            ['foo', 'baabazfaa'],
            ['foo', 'baafoofaa'],
            ['1', [1, '1', 3], true],
        ];
    }
}
