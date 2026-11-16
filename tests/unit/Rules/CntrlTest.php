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
use Respect\Validation\Exceptions\CntrlException;
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Rules\Cntrl;

#[CoversClass(Cntrl::class)]
#[CoversClass(CntrlException::class)]
#[Group('rule')]
class CntrlTest extends TestCase
{
    #[DataProvider('providerForValidCntrl')]
    public function testValidDataWithCntrlShouldReturnTrue($validCntrl, $additional = '')
    {
        $validator = new Cntrl($additional);
        static::assertTrue($validator->validate($validCntrl));
    }

    #[DataProvider('providerForInvalidCntrl')]
    public function testInvalidCntrlShouldFailAndThrowCntrlException($invalidCntrl, $additional = '')
    {
        $this->expectException(CntrlException::class);
        $validator = new Cntrl($additional);
        static::assertFalse($validator->validate($invalidCntrl));
        static::assertFalse($validator->assert($invalidCntrl));
    }

    /**
     * @throws ComponentException
     */
    #[DataProvider('providerForInvalidParams')]
    public function testInvalidConstructorParamsShouldThrowComponentExceptionUponInstantiation($additional)
    {
        $this->expectException(ComponentException::class);
        new Cntrl($additional);
    }

    /**
     * @throws ComponentException
     */
    #[DataProvider('providerAdditionalChars')]
    public function testAdditionalCharsShouldBeRespected($additional, $query)
    {
        $validator = new Cntrl($additional);
        static::assertTrue($validator->validate($query));
    }

    public static function providerAdditionalChars(): array
    {
        return [
            ['!@#$%^&*(){} ', '!@#$%^&*(){} '],
            ['[]?+=/\\-_|"\',<>. ', "[]?+=/\\-_|\"',<>. \t \n"],
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

    public static function providerForValidCntrl(): array
    {
        return [
            ["\n"],
            ["\r"],
            ["\t"],
            ["\n\r\t"],
        ];
    }

    public static function providerForInvalidCntrl(): array
    {
        return [
            [''],
            ['16-50'],
            ['a'],
            [' '],
            ['Foo'],
            ['12.1'],
            ['-12'],
            [-12],
            ['alganet'],
        ];
    }
}
