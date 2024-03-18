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
use Respect\Validation\Rules\Tld;

/**
 * @group  rule
 * @covers Tld
 */
class TldTest extends TestCase
{
    public static function providerForValidTld()
    {
        return [
            ['com'],
            ['cafe'],
            ['democrat'],
            ['br'],
            ['us'],
            ['eu'],
        ];
    }

    /**
     * @dataProvider providerForValidTld
     */
    public function testShouldValidateInputWhenItIsAValidTld($input)
    {
        $rule = new Tld();

        static::assertTrue($rule->validate($input));
    }

    public static function providerForInvalidTld()
    {
        return [
            ['1'],
            [1.0],
            ['wrongtld'],
            [true],
        ];
    }

    /**
     * @dataProvider providerForInvalidTld
     */
    public function testShouldInvalidateInputWhenItIsNotAValidTld($input)
    {
        $rule = new Tld();

        static::assertFalse($rule->validate($input));
    }
}
