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
use Respect\Validation\Rules\NotOptional;
use stdClass;

/**
 * @group  rule
 * @covers NotOptional
 */
class NotOptionalTest extends TestCase
{
    /**
     * @dataProvider providerForNotOptional
     */
    public function testShouldValidateWhenNotOptional($input): void
    {
        $rule = new NotOptional();

        static::assertTrue($rule->validate($input));
    }

    /**
     * @dataProvider providerForOptional
     */
    public function testShouldNotValidateWhenOptional($input): void
    {
        $rule = new NotOptional();

        static::assertFalse($rule->validate($input));
    }

    public static function providerForNotOptional(): array
    {
        return [
            [[]],
            [' '],
            [0],
            ['0'],
            [0],
            ['0.0'],
            [false],
            [['']],
            [[' ']],
            [[0]],
            [['0']],
            [[false]],
            [[[''], [0]]],
            [new stdClass()],
        ];
    }

    public static function providerForOptional(): array
    {
        return [
            [null],
            [''],
        ];
    }
}
