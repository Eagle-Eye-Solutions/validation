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
use Respect\Validation\Exceptions\ComponentException as ComponentExceptionAlias;
use Respect\Validation\Exceptions\PrntException;
use Respect\Validation\Rules\Prnt;

/**
 * @group  rule
 * @covers Prnt
 * @covers PrntException
 */
class PrntTest extends TestCase
{
    /**
     * @dataProvider providerForValidPrint
     */
    public function testValidDataWithPrintCharsShouldReturnTrue($validPrint, $additional = ''): void
    {
        $validator = new Prnt($additional);
        static::assertTrue($validator->validate($validPrint));
    }

    /**
     * @dataProvider providerForInvalidPrint
     * @throws ComponentExceptionAlias
     */
    public function testInvalidPrintShouldFailAndThrowPrntException($invalidPrint, $additional = ''): void
    {
        $this->expectException(PrntException::class);
        $validator = new Prnt($additional);
        static::assertFalse($validator->validate($invalidPrint));
        static::assertFalse($validator->assert($invalidPrint));
    }

    /**
     * @dataProvider providerForInvalidParams
     */
    public function testInvalidConstructorParamsn($additional): void
    {
        $this->expectException(ComponentExceptionAlias::class);
        new Prnt($additional);
    }

    /**
     * @dataProvider providerAdditionalChars
     * @throws ComponentExceptionAlias
     */
    public function testAdditionalCharsShouldBeRespected($additional, $query): void
    {
        $validator = new Prnt($additional);
        static::assertTrue($validator->validate($query));
    }

    public static function providerAdditionalChars(): array
    {
        return [
            ["\t\n", "\t\n "],
            ["\v\r", "\v\r "],
        ];
    }

    public static function providerForInvalidParams(): array
    {
        return [
            [new \stdClass()],
            [[]],
            [0x2],
        ];
    }

    public static function providerForValidPrint(): array
    {
        return [
            [' '],
            ['LKA#@%.54'],
            ['foobar'],
            ['16-50'],
            ['123'],
            ['foo bar'],
            ['#$%&*_'],
        ];
    }

    public static function providerForInvalidPrint(): array
    {
        return [
            [''],
            [null],
            ['foo'.chr(7).'bar'],
            ['foo'.chr(10).'bar'],
        ];
    }
}
