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
use Respect\Validation\Exceptions\AlphaException;
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Rules\Alpha;

/**
 * @group  rule
 * @covers Alpha
 * @covers AlphaException
 */
class AlphaTest extends TestCase
{
    /**
     * @dataProvider providerForValidAlpha
     */
    public function testValidAlphanumericCharsShouldReturnTrue($validAlpha, $additional)
    {
        $validator = new Alpha($additional);
        static::assertTrue($validator->validate($validAlpha));
        static::assertTrue($validator->check($validAlpha));
        static::assertTrue($validator->assert($validAlpha));
    }

    /**
     * @dataProvider providerForInvalidAlpha
     *
     */
    public function testInvalidAlphanumericCharsShouldThrowAlphaException($invalidAlpha, $additional)
    {
        $this->expectException(AlphaException::class);
        $validator = new Alpha($additional);
        static::assertFalse($validator->validate($invalidAlpha));
        static::assertFalse($validator->assert($invalidAlpha));
    }

    /**
     * @dataProvider providerForInvalidParams
     *
     */
    public function testInvalidConstructorParamsShouldThrowComponentException($additional)
    {
        $this->expectException(ComponentException::class);
         new Alpha($additional);
    }

    /**
     * @dataProvider providerAdditionalChars
     */
    public function testAdditionalCharsShouldBeRespected($additional, $query)
    {
        $validator = new Alpha($additional);
        static::assertTrue($validator->validate($query));
    }

    public static function providerAdditionalChars()
    {
        return [
            ['!@#$%^&*(){}', '!@#$%^&*(){} abc'],
            ['[]?+=/\\-_|"\',<>.', "[]?+=/\\-_|\"',<>. \t \n abc"],
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

    public static function providerForValidAlpha()
    {
        return [
            ['alganet', ''],
            ['alganet', 'alganet'],
            ['0alg-anet0', '0-9'],
            ['a', ''],
            ["\t", ''],
            ["\n", ''],
            ['foobar', ''],
            ['rubinho_', '_'],
            ['google.com', '.'],
            ['alganet alganet', ''],
            ["\nabc", ''],
            ["\tdef", ''],
            ["\nabc \t", ''],
        ];
    }

    public static function providerForInvalidAlpha()
    {
        return [
            ['', ''],
            ['@#$', ''],
            ['_', ''],
            ['dgç', ''],
            ['122al', ''],
            ['122', ''],
            [11123, ''],
            [1e21, ''],
            [0, ''],
            [null, ''],
            [new \stdClass(), ''],
            [[], ''],
        ];
    }
}
