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
use Respect\Validation\Exceptions\InException;
use Respect\Validation\Rules\In;

/**
 * @group  rule
 * @covers In
 * @covers InException
 */
class InTest extends TestCase
{
    /**
     * @dataProvider providerForIn
     * @throws \Exception
     */
    public function testSuccessInValidatorCases($input, $options = null): void
    {
        $v = new In($options);
        static::assertTrue($v->__invoke($input));
        static::assertTrue($v->check($input));
        static::assertTrue($v->assert($input));
    }

    /**
     * @dataProvider providerForNotIn
     * @throws \Exception
     */
    public function testInvalidInChecksShouldThrowInException($input, $options, $strict = false): void
    {
        $this->expectException(InException::class);
        $v = new In($options, $strict);
        static::assertFalse($v->__invoke($input));
        static::assertFalse($v->assert($input));
    }

    /**
     * @throws \Exception
     */
    public function testInCheckExceptionMessageWithArray(): void
    {
        $this->expectExceptionMessage("\"x\" must be in { \"foo\", \"bar\" }");
        $this->expectException(InException::class);
        $v = new In(['foo', 'bar']);
        $v->assert('x');
    }

    public static function providerForIn(): array
    {
        return [
            ['', ['']],
            [null, [null]],
            ['0', ['0']],
            [0, [0]],
            ['foo', ['foo', 'bar']],
            ['foo', 'barfoobaz'],
            ['foo', 'foobarbaz'],
            ['foo', 'barbazfoo'],
            ['1', [1, 2, 3]],
            ['1', ['1', 2, 3], true],
        ];
    }

    public static function providerForNotIn(): array
    {
        return [
            [null, '0'],
            [null, 0, true],
            [null, '', true],
            [null, []],
            ['', 'barfoobaz'],
            [null, 'barfoobaz'],
            [0, 'barfoobaz'],
            ['0', 'barfoobaz'],
            ['bat', ['foo', 'bar']],
            ['foo', 'barfaabaz'],
            ['foo', 'faabarbaz'],
            ['foo', 'baabazfaa'],
            ['1', [1, 2, 3], true],
        ];
    }
}
