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

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\ComponentException as ComponentExceptionAlias;
use Respect\Validation\Exceptions\PrntException;
use Respect\Validation\Rules\Prnt;

#[CoversClass(Prnt::class)]
#[CoversClass(PrntException::class)]
#[Group('rule')]
class PrntTest extends TestCase
{
    #[DataProvider('providerForValidPrint')]
    public function testValidDataWithPrintCharsShouldReturnTrue($validPrint, $additional = ''): void
    {
        $validator = new Prnt($additional);
        static::assertTrue($validator->validate($validPrint));
    }

    /**
     * @throws ComponentExceptionAlias
     */
    #[DataProvider('providerForInvalidPrint')]
    public function testInvalidPrintShouldFailAndThrowPrntException($invalidPrint, $additional = ''): void
    {
        $this->expectException(PrntException::class);
        $validator = new Prnt($additional);
        static::assertFalse($validator->validate($invalidPrint));
        static::assertFalse($validator->assert($invalidPrint));
    }

    #[DataProvider('providerForInvalidParams')]
    public function testInvalidConstructorParamsn($additional): void
    {
        $this->expectException(ComponentExceptionAlias::class);
        new Prnt($additional);
    }

    /**
     * @throws ComponentExceptionAlias
     */
    #[DataProvider('providerAdditionalChars')]
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
