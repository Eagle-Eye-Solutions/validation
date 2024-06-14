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
use Respect\Validation\Exceptions\CntrlException;
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Rules\Cntrl;

/**
 * @group  rule
 * @covers Cntrl
 * @covers CntrlException
 */
class CntrlTest extends TestCase
{
    /**
     * @dataProvider providerForValidCntrl
     */
    public function testValidDataWithCntrlShouldReturnTrue($validCntrl, $additional = '')
    {
        $validator = new Cntrl($additional);
        static::assertTrue($validator->validate($validCntrl));
    }

    /**
     * @dataProvider providerForInvalidCntrl
     */
    public function testInvalidCntrlShouldFailAndThrowCntrlException($invalidCntrl, $additional = '')
    {
        $this->expectException(CntrlException::class);
        $validator = new Cntrl($additional);
        static::assertFalse($validator->validate($invalidCntrl));
        static::assertFalse($validator->assert($invalidCntrl));
    }

    /**
     * @dataProvider providerForInvalidParams
     * @throws ComponentException
     */
    public function testInvalidConstructorParamsShouldThrowComponentExceptionUponInstantiation($additional)
    {
        $this->expectException(ComponentException::class);
        new Cntrl($additional);
    }

    /**
     * @dataProvider providerAdditionalChars
     * @throws ComponentException
     */
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
