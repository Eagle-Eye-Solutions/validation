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
use Respect\Validation\Exceptions\UppercaseException;
use Respect\Validation\Rules\Uppercase;

/**
 * @group  rule
 * @covers Uppercase
 * @covers UppercaseException
 */
class UppercaseTest extends TestCase
{
    /**
     * @dataProvider providerForValidUppercase
     */
    public function testValidUppercaseShouldReturnTrue($input): void
    {
        $uppercase = new Uppercase();
        static::assertTrue($uppercase->validate($input));
        static::assertTrue($uppercase->assert($input));
        static::assertTrue($uppercase->check($input));
    }

    /**
     * @dataProvider providerForInvalidUppercase
     */
    public function testInvalidUppercaseShouldThrowException($input): void
    {
        $this->expectException(UppercaseException::class);
        $lowercase = new Uppercase();
        static::assertFalse($lowercase->validate($input));
        static::assertFalse($lowercase->assert($input));
    }

    public static function providerForValidUppercase(): array
    {
        return [
            [''],
            ['UPPERCASE'],
            ['UPPERCASE-WITH-DASHES'],
            ['UPPERCASE WITH SPACES'],
            ['UPPERCASE WITH NUMBERS 123'],
            ['UPPERCASE WITH SPECIALS CHARACTERS LIKE Ã Ç Ê'],
            ['WITH SPECIALS CHARACTERS LIKE # $ % & * +'],
            ['ΤΆΧΙΣΤΗ ΑΛΏΠΗΞ ΒΑΦΉΣ ΨΗΜΈΝΗ ΓΗ, ΔΡΑΣΚΕΛΊΖΕΙ ΥΠΈΡ ΝΩΘΡΟΎ ΚΥΝΌΣ'],
        ];
    }

    public static function providerForInvalidUppercase(): array
    {
        return [
            ['lowercase'],
            ['CamelCase'],
            ['First Character Uppercase'],
            ['With Numbers 1 2 3'],
        ];
    }
}
