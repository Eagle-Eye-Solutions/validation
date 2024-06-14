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
use Respect\Validation\Exceptions\DigitException;
use Respect\Validation\Rules\Digit;

/**
 * @group  rule
 * @covers Digit
 * @covers DigitException
 */
class DigitTest extends TestCase
{
    /**
     * @dataProvider providerForValidDigits
     */
    public function testValidDataWithDigitsShouldReturnTrue($validDigits, $additional = '')
    {
        $validator = new Digit($additional);
        static::assertTrue($validator->validate($validDigits));
    }

    /**
     * @dataProvider providerForInvalidDigits
     *
     */
    public function testInvalidDigitsShouldFailAndThrowDigitException($invalidDigits, $additional = '')
    {
        $this->expectException(DigitException::class);
        $validator = new Digit($additional);
        static::assertFalse($validator->validate($invalidDigits));
        static::assertFalse($validator->assert($invalidDigits));
    }

    /**
     * @dataProvider providerForInvalidParams
     */
    public function testInvalidConstructorParamsShouldThrowComponentExceptionUponInstantiation($additional)
    {
        $this->expectException(ComponentException::class);
        new Digit($additional);
    }

    /**
     * @dataProvider providerAdditionalChars
     */
    public function testAdditionalCharsShouldBeRespected($additional, $query)
    {
        $validator = new Digit($additional);
        static::assertTrue($validator->validate($query));
    }

    public static function providerAdditionalChars()
    {
        return [
            ['!@#$%^&*(){}', '!@#$%^&*(){} 123'],
            ['[]?+=/\\-_|"\',<>.', "[]?+=/\\-_|\"',<>. \t \n 123"],
        ];
    }

    public static function providerForInvalidParams()
    {
        return [
            [new \stdClass()],
            [[]],
            [0x2],
        ];
    }

    public static function providerForValidDigits()
    {
        return [
            ["\n\t"],
            [' '],
            [165],
            [1650],
            ['01650'],
            ['165'],
            ['1650'],
            ['16 50'],
            ["\n5\t"],
            ['16-50', '-'],
        ];
    }

    public static function providerForInvalidDigits()
    {
        return [
            [''],
            [null],
            ['16-50'],
            ['a'],
            ['Foo'],
            ['12.1'],
            ['-12'],
            [-12],
        ];
    }
}
