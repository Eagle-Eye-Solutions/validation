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
use Respect\Validation\Exceptions\AlnumException;
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Rules\Alnum;

/**
 * @group  rule
 * @covers Alnum
 * @covers AlnumException
 */
class AlnumTest extends TestCase
{
    /**
     * @dataProvider providerForValidAlnum
     */
    public function testValidAlnumCharsShouldReturnTrue($validAlnum, $additional)
    {
        $validator = new Alnum($additional);
        static::assertTrue($validator->validate($validAlnum));
    }

    /**
     * @dataProvider providerForInvalidAlnum
     *
     */
    public function testInvalidAlnumCharsShouldThrowAlnumExceptionAndReturnFalse($invalidAlnum, $additional)
    {
        $this->expectException(AlnumException::class);
        $validator = new Alnum($additional);
        static::assertFalse($validator->validate($invalidAlnum));
        static::assertFalse($validator->assert($invalidAlnum));
    }

    /**
     * @dataProvider providerForInvalidParams
     *
     */
    public function testInvalidConstructorParamsShouldThrowComponentExceptionUponInstantiation($additional)
    {
        $this->expectException(ComponentException::class);
        new Alnum($additional);
    }

    /**
     * @dataProvider providerAdditionalChars
     * @throws ComponentException
     */
    public function testAdditionalCharsShouldBeRespected($additional, $query): void
    {
        $validator = new Alnum($additional);
        static::assertTrue($validator->validate($query));
    }

    public static function providerAdditionalChars()
    {
        return [
            ['!@#$%^&*(){}', '!@#$%^&*(){} abc 123'],
            ['[]?+=/\\-_|"\',<>.', "[]?+=/\\-_|\"',<>. \t \n abc 123"],
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

    public static function providerForValidAlnum()
    {
        return [
            ['alganet', ''],
            ['alganet', 'alganet'],
            ['0alg-anet0', '0-9'],
            ['1', ''],
            ["\t", ''],
            ["\n", ''],
            ['a', ''],
            ['foobar', ''],
            ['rubinho_', '_'],
            ['google.com', '.'],
            ['alganet alganet', ''],
            ["\nabc", ''],
            ["\tdef", ''],
            ["\nabc \t", ''],
            [0, ''],
        ];
    }

    public static function providerForInvalidAlnum()
    {
        return [
            ['', ''],
            ['@#$', ''],
            ['_', ''],
            ['dgç', ''],
            [1e21, ''], //evaluates to "1.0E+21"
            [null, ''],
            [new \stdClass(), ''],
            [[], ''],
        ];
    }
}
