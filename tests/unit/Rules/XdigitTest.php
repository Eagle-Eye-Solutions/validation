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
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Exceptions\XdigitException;
use Respect\Validation\Rules\Xdigit;

/**
 * @group  rule
 * @covers Xdigit
 * @covers XdigitException
 */
class XdigitTest extends TestCase
{
    protected Xdigit $xdigitsValidator;

    protected function setUp(): void
    {
        $this->xdigitsValidator = new Xdigit();
    }

    /**
     * @dataProvider providerForXdigit
     */
    public function testValidateValidHexasdecimalDigits($input): void
    {
        static::assertTrue($this->xdigitsValidator->assert($input));
        static::assertTrue($this->xdigitsValidator->check($input));
        static::assertTrue($this->xdigitsValidator->validate($input));
    }

    /**
     * @dataProvider providerForNotXdigit
     *
     * @throws \Exception
     */
    public function testInvalidHexadecimalDigitsShouldThrowXdigitException($input): void
    {
        $this->expectException(XdigitException::class);
        static::assertFalse($this->xdigitsValidator->validate($input));
        static::assertFalse($this->xdigitsValidator->assert($input));
    }

    /**
     * @dataProvider providerAdditionalChars
     * @throws ComponentException
     */
    public function testAdditionalCharsShouldBeRespected($additional, $query): void
    {
        $validator = new Xdigit($additional);
        static::assertTrue($validator->validate($query));
    }

    public static function providerAdditionalChars(): array
    {
        return [
            ['!@#$%^&*(){} ', '!@#$%^&*(){} abc 123'],
            ["[]?+=/\\-_|\"',<>. \t\n", "[]?+=/\\-_|\"',<>. \t \n abc 123"],
        ];
    }

    public static function providerForXdigit(): array
    {
        return [
            ['FFF'],
            ['15'],
            ['DE12FA'],
            ['1234567890abcdef'],
            [0x123],
        ];
    }

    public static function providerForNotXdigit(): array
    {
        return [
            [''],
            [null],
            ['j'],
            [' '],
            ['Foo'],
            ['1.5'],
        ];
    }
}
