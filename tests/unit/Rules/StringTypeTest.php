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
use Respect\Validation\Rules\StringType;

/**
 * @group  rule
 * @covers StringType
 * @covers StringTypeException
 */
class StringTypeTest extends TestCase
{
    /**
     * @dataProvider providerForString
     */
    public function testString($input): void
    {
        $rule = new StringType();

        static::assertTrue($rule->validate($input));
    }

    /**
     * @dataProvider providerForNotString
     */
    public function testNotString($input): void
    {
        $rule = new StringType();

        static::assertFalse($rule->validate($input));
    }

    public static function providerForString(): array
    {
        return [
            [''],
            ['165.7'],
        ];
    }

    public static function providerForNotString(): array
    {
        return [
            [null],
            [[]],
            [new \stdClass()],
            [150],
        ];
    }
}
